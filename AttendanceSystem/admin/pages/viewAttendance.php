<?php
require_once __DIR__ . '/../core/models.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') header("Location: ../login.php");

$courseModel = new CourseModel($pdo);
$attendanceModel = new AttendanceModel($pdo);
$courses = $courseModel->getAll();

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'], $_POST['year_level'])) {
    $results = $attendanceModel->getByCourseAndYear((int)$_POST['course_id'], $_POST['year_level']);
}
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
  <title>View Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fdfaf6; }
    h2 { color: #5a3e36; }
    
    .btn-nude1 { 
      background-color: #E6D5B8; 
      color:#5C4033; 
      border:none; 
    }
    .btn-nude1:hover { 
      background-color: #D2B48C; 
      color:#fff; 
    }
    .btn-nude2 { 
      background-color: #F5E1DA; 
      color:#5C4033; 
      border:none; 
    }
    .btn-nude2:hover { 
      background-color: #E6CFC7; 
      color:#fff; 
    }

    .badge-ontime { 
      background-color: #CBBFAD; 
      color:#2E4632; 
      padding: 6px 10px; 
      border-radius: 12px; 
    }
    .badge-late { 
      background-color: #E6CFC7; 
      color:#8B0000; 
      padding: 6px 10px; 
      border-radius: 12px; 
    }
  </style>

</head>
<body>
<div class="container py-4">
  <button type="button" class="btn btn-nude1 mb-3" onclick="window.history.back();">← Back</button>
  <h2>Check Attendance by Course & Year</h2>
  
  <form method="post" class="row g-2 mb-3">
    <div class="col-md-4">
      <select name="course_id" class="form-select" required>
        <option value="">Select course</option>
        <?php foreach($courses as $c): ?>
          <option value="<?=$c['id']?>" <?= (isset($_POST['course_id']) && $_POST['course_id'] == $c['id']) ? 'selected' : '' ?>>
            <?=htmlspecialchars($c['code'].' - '.$c['name'])?>
          </option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="col-md-2">
      <input name="year_level" class="form-control" placeholder="Year Level" value="<?= $_POST['year_level'] ?? '' ?>" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-nude2">Check</button>
    </div>
  </form>

  <?php if(!empty($results)): ?>
    <table class="table mt-3">
      <thead>
        <tr>
          <th>Student</th>
          <th>Email</th>
          <th>Filed At</th>
          <th>Late?</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($results as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['student_name'])?></td>
            <td><?=htmlspecialchars($r['email'])?></td>
            <td><?=htmlspecialchars($r['filed_at'])?></td>
            <td>
              <?= $r['is_late'] 
                  ? '<span class="badge-late">LATE</span>' 
                  : '<span class="badge-ontime">ON TIME</span>' ?>
            </td>
            <td><?=htmlspecialchars($r['remarks'])?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</div>
</body>
</html>
