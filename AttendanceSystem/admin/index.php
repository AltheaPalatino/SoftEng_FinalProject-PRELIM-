<?php
require_once __DIR__.'/core/models.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<?php
if (!isset($_SESSION)) session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand text-white" href="/AttendanceSystem/Admin/index.php">Admin Panel</a>
    <div>
      <?php if(isset($_SESSION['user_id'])): ?>
        <span class="me-2 text-white">Hello, <?=htmlspecialchars($_SESSION['name'])?></span>
        <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn-sm btn-light" href="/AttendanceSystem/Admin/login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!doctype html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .btn-nude1 {
      background-color: #E6D5B8;
      color: #5C4033;
      border: none;
    }
    .btn-nude1:hover {
      background-color: #D2B48C; 
      color: #fff;
    }
    .btn-nude2 {
      background-color: #F5E1DA; 
      color: #5C4033;
      border: none;
    }
    .btn-nude2:hover {
      background-color: #E6CFC7;
      color: #fff;
    }
    .btn-nude3 {
      background-color: #CBBFAD;       
      color: #3B2F2F;
      border: none;
    }
    .btn-nude3:hover {
      background-color: #B5A189; 
      color: #fff;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Admin Dashboard</h1>
  <div class="mt-3">
    <a class="btn btn-nude1" href="pages/manageCourses.php">Manage Courses</a>
    <a class="btn btn-nude2" href="pages/students.php">Students</a>
    <a class="btn btn-nude3" href="pages/viewAttendance.php">View Attendance</a>
    <a class="btn btn-nude3" href="pages/manageExcuses.php">Students Excuse Letter</a>
  </div>

</div>
</body>
</html>
