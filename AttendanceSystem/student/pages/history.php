<?php
require_once __DIR__ . '/../core/models.php';
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
$attendanceModel = new AttendanceModel($pdo);
$history = $attendanceModel->getHistoryByUser($_SESSION['user_id']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand text-white" href="/Attendance_System/Student/index.php">Student Panel</a>
    <div>
      <?php if(isset($_SESSION['user_id'])): ?>
        <span class="me-2 text-white">Hello, <?=htmlspecialchars($_SESSION['name'])?></span>
      <?php else: ?>
        <a class="btn btn-sm btn-light" href="/Attendance_System/Student/login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!doctype html>
<html>
<head>
  <title>Attendance History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fdfaf6; }
    h2 { color: #5a3e36; }
    .table thead { background-color: #f8eadd; color: #5a3e36; }
    .badge.bg-success { background-color: #c89f94 !important; }
    .badge.bg-danger { background-color: #a77b71 !important; }
    .btn-secondary {
      background-color: #d8c3b0;
      border: none;
    }
    .btn-secondary:hover {
      background-color: #b89f8f;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <button type="button" class="btn btn-secondary mb-3" onclick="window.history.back();">Back</button>
  <h2>Your Attendance History</h2>
  <table class="table table-bordered">
    <thead><tr><th>Course</th><th>Filed At</th><th>Late?</th><th>Remarks</th></tr></thead>
    <tbody>
      <?php foreach($history as $h): ?>
        <tr>
          <td><?=htmlspecialchars($h['course_code'].' - '.$h['course_name'])?></td>
          <td><?=htmlspecialchars($h['filed_at'])?></td>
          <td><?= $h['is_late'] ? '<span class="badge bg-danger">LATE</span>' : '<span class="badge bg-success">ON TIME</span>' ?></td>
          <td><?=htmlspecialchars($h['remarks'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
