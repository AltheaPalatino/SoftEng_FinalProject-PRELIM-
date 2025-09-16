<?php
require_once __DIR__ . "/classes/Database.php";
require_once __DIR__ . "/classes/Category.php";

$db = new Database();
$pdo = $db->getConnection();
$categoryObj = new Category($pdo);

// Handle adding category directly here
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === "add_category") {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $categoryObj->addCategory($name);
        header("Location: categories.php?success=1");
        exit;
    }
}

$categories = $categoryObj->getAllCategories();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/categories.css"> 
</head>
  <?php include 'includes/navbar.php'; ?>
<body>


  <h2 >Manage Categories</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Category added successfully!</div>
  <?php endif; ?>

  <!-- Add Category Form -->
  <form method="POST" class="mb-4">
    <input type="hidden" name="action" value="add_category">
    <div class="mb-3">
      <label class="form-label">Category Name</label>
      <input type="text" name="category_name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Category</button>
  </form>

  <!-- Category List -->
  <h4>Existing Categories</h4>
  <ul class="list-group">
    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $cat): ?>
        <li class="list-group-item"><?= htmlspecialchars($cat['category_name']) ?></li>
      <?php endforeach; ?>
    <?php else: ?>
      <li class="list-group-item">No categories found.</li>
    <?php endif; ?>
  </ul>

</body>
</html>
