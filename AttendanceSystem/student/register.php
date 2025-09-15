<?php
require_once __DIR__ . '/core/dbConfig.php';   // ensure $pdo exists
require_once __DIR__ . '/core/models.php';
require_once __DIR__ . '/core/handleForms.php';

$courseModel = new CourseModel($pdo);
$courses = $courseModel->getAll();

if (!isset($_SESSION)) session_start();
?>
<!doctype html>
<html>
<head>
  <title>Student Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .btn-nude {
      background-color: #E6D5B8;
      color: #5C4033;
      border: none;
    }
    .btn-nude:hover {
      background-color: #D2B48C;
      color: #fff;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h2>Register</h2>

  <?php if(!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <form method="post" action="core/handleForms.php">
    <input type="hidden" name="action" value="register">

    <div class="mb-2">
      <input class="form-control" name="name" placeholder="Full name" required>
    </div>

    <div class="mb-2">
      <input class="form-control" name="email" placeholder="Email" type="email" required>
    </div>

    <div class="mb-2">
      <input class="form-control" name="password" placeholder="Password" type="password" required>
    </div>

    <input type="hidden" name="role" value="student">

    <div class="mb-2">
      <select class="form-select" name="course_id" required>
        <option value="">Select Course</option>
        <?php foreach($courses as $c): ?>
          <option value="<?= $c['id'] ?>">
            <?= htmlspecialchars($c['code'].' - '.$c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-2">
      <input class="form-control" name="year_level" placeholder="Year level (e.g. 1,2,3)" required>
    </div>

    <button class="btn btn-nude">Register</button>
  </form>

  <hr>
  <a href="login.php">Already have account? Login</a>
</div>
</body>
</html>
