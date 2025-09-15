<?php
require_once __DIR__ . '/../core/models.php';
require_once __DIR__ . '/../core/handleForms.php';
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
$courseModel = new CourseModel($pdo);
$courses = $courseModel->getAll();
$userModel = new UserModel($pdo);
$user = $userModel->getById($_SESSION['user_id']);
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
  <title>File Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fdfaf6; }
    h2 { color: #5a3e36; }
    .card {
      background-color: #f8eadd;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0,0,0,.1);
      padding: 20px;
    }
    .btn-primary {
      background-color: #c89f94;
      border: none;
    }
    .btn-primary:hover {
      background-color: #a77b71;
    }
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
  <div class="card">
    <h2>File Attendance</h2>
    <?php if(!empty($_SESSION['success'])){ echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); } ?>
    <form method="post" action="../core/handleForms.php">
      <input type="hidden" name="action" value="file_attendance">
      <div class="mb-2">
        <select name="course_id" class="form-select" required>
          <?php foreach($courses as $c): ?>
            <option value="<?=$c['id']?>" <?=($user['course_id']==$c['id'])?'selected':''?>><?=htmlspecialchars($c['code'].' - '.$c['name'])?></option>
          <?php endforeach;?>
        </select>
      </div>
      <div class="mb-2"><input name="year_level" class="form-control" value="<?=htmlspecialchars($user['year_level'])?>" required></div>
      <div class="mb-2"><textarea name="remarks" class="form-control" placeholder="Remarks (optional)"></textarea></div>
      <button class="btn btn-primary">File Attendance Now</button>
    </form>
  </div>
</div>
</body>
</html>
