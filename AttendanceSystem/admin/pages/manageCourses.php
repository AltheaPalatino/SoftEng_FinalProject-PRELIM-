<?php
require_once __DIR__ . '/../core/models.php';
require_once __DIR__ . '/../core/handleForms.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') header("Location: ../login.php");

$courseModel = new CourseModel($pdo);
$courses = $courseModel->getAll();

// Check if edit mode
$editCourse = null;
if (isset($_GET['edit'])) {
    $editCourse = $courseModel->getById((int)$_GET['edit']);
}
?>

<!doctype html>
<html>
<head>
  <title>Manage Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fdfaf6; }
    h2 { color: #5a3e36; }
    .btn-nude1 { background-color: #E6D5B8; color:#5C4033; border:none; }
    .btn-nude1:hover { background-color: #D2B48C; color:#fff; }
    .btn-nude2 { background-color: #F5E1DA; color:#5C4033; border:none; }
    .btn-nude2:hover { background-color: #E6CFC7; color:#fff; }
    .btn-nude3 { background-color: #CBBFAD; color:#3B2F2F; border:none; }
    .btn-nude3:hover { background-color: #B5A189; color:#fff; }
    .card { background-color: #f8eadd; border-radius: 12px; padding: 20px; }
  </style>
</head>
<body>
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

<div class="container py-4">
  <button type="button" class="btn btn-nude1 mb-3" onclick="window.history.back();">← Back</button>

  <?php if(!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <!-- ADD / EDIT FORM -->
  <div class="card mb-3">
    <?php if($editCourse): ?>
      <h2>Edit Course</h2>
      <form method="post" action="../core/handleForms.php">
        <input type="hidden" name="action" value="edit_course">
        <input type="hidden" name="id" value="<?= $editCourse['id'] ?>">
        <div class="mb-2"><input name="code" class="form-control" placeholder="Course Code" value="<?= htmlspecialchars($editCourse['code']) ?>" required></div>
        <div class="mb-2"><input name="name" class="form-control" placeholder="Course Name" value="<?= htmlspecialchars($editCourse['name']) ?>" required></div>
        <div class="mb-2"><input name="start_time" type="time" class="form-control" value="<?= htmlspecialchars($editCourse['start_time']) ?>" required></div>
        <div class="mb-2"><input name="late_grace_minutes" type="number" class="form-control" value="<?= htmlspecialchars($editCourse['late_grace_minutes']) ?>" required></div>
        <button class="btn btn-nude3">Update</button>
        <a class="btn btn-secondary" href="manageCourses.php">Cancel</a>
      </form>
    <?php else: ?>
      <h2>Manage Courses</h2>
      <button class="btn btn-nude2 mb-2" data-bs-toggle="collapse" data-bs-target="#addCourseForm">Add Course</button>
      <div id="addCourseForm" class="collapse">
        <form method="post" action="../core/handleForms.php" class="mt-2">
          <input type="hidden" name="action" value="add_course">
          <div class="mb-2"><input name="code" class="form-control" placeholder="Course Code" required></div>
          <div class="mb-2"><input name="name" class="form-control" placeholder="Course Name" required></div>
          <div class="mb-2"><input name="start_time" type="time" class="form-control" value="08:00:00" required></div>
          <div class="mb-2"><input name="late_grace_minutes" type="number" class="form-control" value="10" required></div>
          <button class="btn btn-nude3">Save</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <!-- COURSES TABLE -->
  <table class="table table-striped mt-3">
    <thead class="table-light"><tr><th>Code</th><th>Name</th><th>Start</th><th>Grace (min)</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach($courses as $c): ?>
        <tr>
          <td><?=htmlspecialchars($c['code'])?></td>
          <td><?=htmlspecialchars($c['name'])?></td>
          <td><?=htmlspecialchars($c['start_time'])?></td>
          <td><?=htmlspecialchars($c['late_grace_minutes'])?></td>
          <td>
            <a class="btn btn-sm btn-nude1" href="manageCourses.php?edit=<?= $c['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-nude2" href="../core/handleForms.php?delete_course=<?= $c['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
