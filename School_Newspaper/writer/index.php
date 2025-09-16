<?php 
require_once 'classloader.php'; 
require_once 'classes/Category.php';

$db = new Database();
$pdo = $db->getConnection();

$categoryObj = new Category($pdo);

$categories = $categoryObj->getAllCategories();


if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
    exit;
}  

$articles = $articleObj->getActiveArticlesWithUsers();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Writer Dashboard</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/index.css"> 
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center mt-4 mb-4">
      Hello there and welcome! 
      <span class="text-warning"><?= htmlspecialchars($_SESSION['username']); ?></span>. 
      Here are all the articles ✨
    </div>
    <img src="https://i.postimg.cc/XvFxKmmZ/6aa2b004b78a62beabaa2b93c7fb0497-removebg-preview.png" class="header-icon" alt="School Icon">

    <div class="row justify-content-center">
      <div class="col-md-6">

        <!-- Article submission form -->
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <input type="text" class="form-control mt-3" name="title" placeholder="📝 Input title here" required>
          </div>
          <div class="form-group">
            <textarea name="content" class="form-control mt-3" placeholder="📖 Write your article" rows="5" required></textarea>
          </div>
          <div class="form-group">
            <label><strong>📷 Upload an image (optional)</strong></label>
            <input type="file" name="image" class="form-control-file">
          </div>
          <div class="form-group">
            <label><i class="bi bi-tags"></i> Select Category:</label>
            <select name="category_id" class="form-control" required>
              <option value="">-- Select Category --</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id']; ?>">
                  <?= htmlspecialchars($cat['category_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="submit" class="btn btn-primary form-control mt-4 mb-4" name="insertArticleBtn" value="Publish 🚀">
        </form> 

        <!-- HAVE CHANGE Display Active Articles -->
        <?php
        $activeArticles = $articleObj->getArticlesByUserID($_SESSION['user_id']);
        foreach ($activeArticles as $article): ?>
          <div class="card mt-3 shadow">
            <div class="card-body">
              <h4 class="text-primary">📖<?= htmlspecialchars($article['title']); ?></h4>
              <small class="text-muted">
                <?= htmlspecialchars($article['username']); ?> - <?= htmlspecialchars($article['created_at']); ?> 
                | Category: <strong><?= htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?></strong>
              </small>

              <?php if (!empty($article['image_path'])): ?>
                <div class="text-center mb-2">
                  <img src="../uploads/<?= htmlspecialchars($article['image_path']); ?>" class="img-fluid rounded" alt="Article Image">
                </div>
              <?php endif; ?>
              <p><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))); ?>...</p>
            </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
</body>
</html>
