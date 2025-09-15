<?php

require_once __DIR__ . '/models.php';
if (!isset($_SESSION)) session_start();

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
    header("Location: ../index.php"); // correct admin dashboard
} else {
    header("Location: ../../Student/index.php"); // correct student dashboard
}
exit;
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
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

//EXCUSE LETTER
if (isset($_POST['action']) && $_POST['action'] === 'update_excuse_status') {
    $excuse_id = $_POST['excuse_id'];
    $status = $_POST['status'];

    if (updateExcuseStatus($pdo, $excuse_id, $status)) {
        $_SESSION['success'] = "Excuse status updated!";
    } else {
        $_SESSION['error'] = "Failed to update excuse status.";
    }
    header("Location: ../pages/manageExcuses.php");
    exit();
}