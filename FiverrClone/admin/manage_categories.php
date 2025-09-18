<?php
if (!isset($_SESSION)) session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Category.php";
require_once __DIR__ . "/core/handleForms.php";

$userObj = new User();

if (!$userObj->isLoggedIn() || !$userObj->isAdmin()) {
    header("Location: login.php");
    exit;
}


$catObj = new Category();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['addCategoryBtn'])) {
        $catObj->addCategory($_POST['category_name']);
    }
    if (isset($_POST['addSubcategoryBtn'])) {
        $catObj->addSubcategory($_POST['category_id'], $_POST['subcategory_name']);
    }
    if (isset($_POST['deleteCategoryBtn'])) {
        $catObj->deleteCategory($_POST['category_id']);
    }
    if (isset($_POST['deleteSubcategoryBtn'])) {
        $catObj->deleteSubcategory($_POST['subcategory_id']);
    }
}

$categories = $catObj->getCategoriesWithSubcategories();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Categories</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf9; 
      color: #5c4b3c; 
    }

    .container {
      background: #fff6f0; 
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1); 
      margin-top: 30px;
    }

    h2 {
      font-weight: 600;
      color: #5c4b3c;
      margin-bottom: 20px;
    }

    .card {
      border: none;
      border-radius: 8px;
      background-color: #f5ebe0; 
      box-shadow: 0 2px 8px rgba(92, 75, 60, 0.08);
    }

    .card-header {
      background-color: #d8c3a5; 
      color: #3e2f21;
      font-weight: 500;
    }

    .list-group-item {
      background-color: #fff6f0;
      color: #5c4b3c;
      border: 1px solid #ecd9c6;
    }

    .list-group-item:hover {
      background-color: #f0e0d0;
    }

    .btn-success {
      background-color: #b89c7a;
      border-color: #b89c7a;
    }

    .btn-success:hover {
      background-color: #a08563;
      border-color: #a08563;
    }

    .btn-primary {
      background-color: #c9b89d;
      border-color: #c9b89d;
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #a89274;
      border-color: #a89274;
    }

    .btn-danger {
      background-color: #d98880;
      border-color: #d98880;
    }

    .btn-danger:hover {
      background-color: #c96f68;
      border-color: #c96f68;
    }

    input.form-control {
      background-color: #fff6f0;
      border: 1px solid #d8c3a5;
      color: #5c4b3c;
    }

    input.form-control:focus {
      background-color: #fff;
      border-color: #b89c7a;
      box-shadow: 0 0 4px rgba(184, 156, 122, 0.5);
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
  <h2>Manage Categories & Subcategories</h2>

  <!-- Add Category -->
  <form method="POST" class="form-inline mb-3">
    <input type="text" name="category_name" class="form-control mr-2" placeholder="New Category" required>
    <button type="submit" name="addCategoryBtn" class="btn btn-success">Add Category</button>
  </form>

  <?php foreach ($categories as $cat): ?>
    <div class="card mt-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong><?= htmlspecialchars($cat['name']) ?></strong>
        <form method="POST" class="m-0">
          <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
          <button type="submit" name="deleteCategoryBtn" class="btn btn-danger btn-sm">Delete</button>
        </form>
      </div>
      <div class="card-body">
        
        <ul class="list-group mb-3">
          <?php foreach ($cat['subcategories'] as $sub): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($sub['name']) ?>
              <form method="POST" class="m-0">
                <input type="hidden" name="subcategory_id" value="<?= $sub['id'] ?>">
                <button type="submit" name="deleteSubcategoryBtn" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </li>
          <?php endforeach; ?>
        </ul>

        <!-- Add Subcategory -->
        <form method="POST" class="form-inline">
          <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
          <input type="text" name="subcategory_name" class="form-control mr-2" placeholder="New Subcategory" required>
          <button type="submit" name="addSubcategoryBtn" class="btn btn-primary">Add Subcategory</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
