<?php
require_once __DIR__ . '/models.php';

$userModel = new UserModel($pdo);
$courseModel = new CourseModel($pdo);
$attendanceModel = new AttendanceModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = isset($_POST['role']) && $_POST['role'] === 'admin' ? 'admin' : 'student';

    $course_id = ($role === 'student' && !empty($_POST['course_id'])) ? (int)$_POST['course_id'] : null;
    $year_level = ($role === 'student' && !empty($_POST['year_level'])) ? $_POST['year_level'] : null;

    if ($userModel->getByEmail($email)) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: ../register.php");
        exit;
    }

    $userModel->createUser([
        'name'       => $name,
        'email'      => $email,
        'password'   => $password,
        'role'       => $role,
        'course_id'  => $course_id,
        'year_level' => $year_level
    ]);

    $_SESSION['success'] = "Registered successfully. Please login.";
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user = $userModel->getByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid credentials.";
        header("Location: ../login.php");
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['name']    = $user['name'];

    if ($user['role'] === 'admin') {
        header("Location: ../index.php");
    } else {
        header("Location: ../../Student/index.php");
    }
    exit;
}

if (isset($_GET['logout'])) {
    if(!isset($_SESSION)) session_start();
    session_unset();
    session_destroy();
    header("Location: /AttendanceSystem/Student/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_course') {
    $data = [
        'code' => trim($_POST['code']),
        'name' => trim($_POST['name']),
        'start_time' => $_POST['start_time'] ?: '08:00:00',
        'late_grace_minutes' => (int)($_POST['late_grace_minutes'] ?? 10)
    ];
    $courseModel->createCourse($data);
    $_SESSION['success'] = "Course added.";
    header("Location: ../pages/manageCourses.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_course') {
    $id = (int)$_POST['id'];
    $data = [
        'code' => trim($_POST['code']),
        'name' => trim($_POST['name']),
        'start_time' => $_POST['start_time'] ?: '08:00:00',
        'late_grace_minutes' => (int)($_POST['late_grace_minutes'] ?? 10)
    ];
    $courseModel->updateCourse($id, $data);
    $_SESSION['success'] = "Course updated.";
    header("Location: ../pages/manageCourses.php");
    exit;
}

if (isset($_GET['delete_course'])) {
    $id = (int)$_GET['delete_course'];
    $courseModel->deleteCourse($id);
    $_SESSION['success'] = "Course deleted.";
    header("Location: ../pages/manageCourses.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'file_attendance') {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please login to file attendance.";
        header("Location: ../login.php");
        exit;
    }
    $user = $userModel->getById($_SESSION['user_id']);
    $course = $courseModel->getById((int)$_POST['course_id']);
    $filed_at = date('Y-m-d H:i:s');
    $filed_time = new DateTime($filed_at);
    $start_time_today = new DateTime($filed_at);
    [$h,$m,$s] = explode(':', $course['start_time']);
    $start_time_today->setTime((int)$h, (int)$m, (int)$s);
    $grace = (int)$course['late_grace_minutes'];
    $start_time_today->modify("+{$grace} minutes");

    $is_late = $filed_time > $start_time_today ? 1 : 0;

    $attendanceModel->fileAttendance([
        'user_id' => $user['id'],
        'course_id' => $course['id'],
        'year_level' => $_POST['year_level'],
        'status' => 'present',
        'filed_at' => $filed_at,
        'is_late' => $is_late,
        'remarks' => $_POST['remarks'] ?? null
    ]);
    $_SESSION['success'] = "Attendance filed." . ($is_late ? " (Marked LATE)" : "");
    header("Location: ../pages/attendance.php");
    exit;
}


// EXCUSE LETTER
if (isset($_POST['action']) && $_POST['action'] === 'submitExcuse') {
    $student_id = $_SESSION['user_id']; 
    $reason = $_POST['reason'];
    $file_path = null;

    // handle file upload
    if (!empty($_FILES['excuse_file']['name'])) {
        $targetDir = "../../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $file_path = $targetDir . time() . "_" . basename($_FILES['excuse_file']['name']);
        move_uploaded_file($_FILES['excuse_file']['tmp_name'], $file_path);
    }

    if (submitExcuseLetter($pdo, $student_id, $reason, $file_path)) {
        $_SESSION['success'] = "Excuse letter submitted successfully!";
    } else {
        $_SESSION['error'] = "Failed to submit excuse letter.";
    }
    header("Location: ../pages/submitExcuse.php");
    exit();
}