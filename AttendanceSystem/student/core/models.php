<?php
require_once __DIR__ . '/dbConfig.php';

class Database {
    protected $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
}

class UserModel extends Database {
    public function createUser($data) {
        $sql = "INSERT INTO users (name, email, password, role, course_id, year_level) 
                VALUES (:name, :email, :password, :role, :course_id, :year_level)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'],
            ':course_id' => $data['course_id'] ?? null,
            ':year_level' => $data['year_level'] ?? null
        ]);
    }

    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT u.*, c.name as course_name, c.code as course_code FROM users u LEFT JOIN courses c ON u.course_id = c.id WHERE u.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function allStudents() {
        $stmt = $this->pdo->prepare("SELECT u.*, c.code as course_code, c.name as course_name FROM users u LEFT JOIN courses c ON u.course_id = c.id WHERE u.role = 'student'");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

class CourseModel extends Database {
    public function createCourse($data) {
        $sql = "INSERT INTO courses (code, name, start_time, late_grace_minutes) VALUES (:code, :name, :start_time, :late_grace_minutes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':start_time' => $data['start_time'],
            ':late_grace_minutes' => $data['late_grace_minutes'] ?? 10
        ]);
    }

    public function updateCourse($id, $data) {
        $sql = "UPDATE courses SET code = :code, name = :name, start_time = :start_time, late_grace_minutes = :late_grace_minutes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':start_time' => $data['start_time'],
            ':late_grace_minutes' => $data['late_grace_minutes'] ?? 10,
            ':id' => $id
        ]);
    }

    public function deleteCourse($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM courses ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}

class AttendanceModel extends Database {
    public function fileAttendance($data) {
        $sql = "INSERT INTO attendance (user_id, course_id, year_level, status, filed_at, is_late, remarks) 
                VALUES (:user_id, :course_id, :year_level, :status, :filed_at, :is_late, :remarks)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':year_level' => $data['year_level'],
            ':status' => $data['status'] ?? 'present',
            ':filed_at' => $data['filed_at'],
            ':is_late' => $data['is_late'] ? 1 : 0,
            ':remarks' => $data['remarks'] ?? null
        ]);
    }

    public function getHistoryByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT a.*, c.code as course_code, c.name as course_name FROM attendance a JOIN courses c ON a.course_id = c.id WHERE a.user_id = :user_id ORDER BY a.filed_at DESC");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function getByCourseAndYear($course_id, $year_level) {
        $stmt = $this->pdo->prepare("SELECT a.*, u.name as student_name, u.email FROM attendance a JOIN users u ON a.user_id = u.id WHERE a.course_id = :course_id AND a.year_level = :year_level ORDER BY a.filed_at DESC");
        $stmt->execute([':course_id' => $course_id, ':year_level' => $year_level]);
        return $stmt->fetchAll();
    }
}

// EXCUSE LETTER
function submitExcuseLetter($pdo, $student_id, $reason, $file_path = null) {
    $sql = "INSERT INTO excuse_letters (student_id, reason, file_path) 
            VALUES (:student_id, :reason, :file_path)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':student_id' => $student_id,
        ':reason' => $reason,
        ':file_path' => $file_path
    ]);
}

// Fetch excuse letter history for a student
function getExcuseHistory($pdo, $student_id) {
    $sql = "SELECT e.*, u.name AS student_name
            FROM excuse_letters e
            JOIN users u ON e.student_id = u.id
            WHERE e.student_id = :student_id
            ORDER BY e.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
