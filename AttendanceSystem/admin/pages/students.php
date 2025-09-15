<?php
require_once __DIR__ . '/../core/models.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') header("Location: ../login.php");
$userModel = new UserModel($pdo);
$students = $userModel->allStudents();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand text-white" href="/Attendance_System/Admin/index.php">Admin Panel</a>
    <div>
      <?php if(isset($_SESSION['user_id'])): ?>
        <span class="me-2 text-white">Hello, <?=htmlspecialchars($_SESSION['name'])?></span>
      <?php else: ?>
        <a class="btn btn-sm btn-light" href="/Attendance_System/Admin/login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!doctype html>
<html>
<head>
  <title>Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { 
      background-color: #fdfaf6; 
    }
    h2 { 
      color: #5a3e36; 
    }
    .btn-nude1 { 
      background-color: #E6D5B8; 
      color:#5C4033; 
      border:none; 
    }
    .btn-nude1:hover { 
      background-color: #D2B48C; 
      color:#fff; 
    }
    .table thead { 
      background-color: #f8eadd; 
      color: #5a3e36; 
    }
  </style>
  
</head>
<body>
<div class="container py-4">
  <button type="button" class="btn btn-nude1 mb-3" onclick="window.history.back();">← Back</button>
  <h2>Students</h2>
  <table class="table table-bordered">
    <thead><tr><th>Name</th><th>Email</th><th>Course</th><th>Year</th></tr></thead>
    <tbody>
      <?php foreach($students as $s): ?>
        <tr>
          <td><?=htmlspecialchars($s['name'])?></td>
          <td><?=htmlspecialchars($s['email'])?></td>
          <td><?=htmlspecialchars($s['course_code'].' '.$s['course_name'])?></td>
          <td><?=htmlspecialchars($s['year_level'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
