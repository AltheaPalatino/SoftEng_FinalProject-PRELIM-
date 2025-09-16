<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/index.css"> 
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="text-center">
      <div class="display-4">
        Hello there and welcome to the admin side! 
        <span><?= $_SESSION['username']; ?></span>. 
        Here are all the articles ✨
      </div>
            <img src="https://i.postimg.cc/XvFxKmmZ/6aa2b004b78a62beabaa2b93c7fb0497-removebg-preview.png" class="header-icon" alt="School Icon">
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <!-- Admin post form -->
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="p-3 bg-white shadow rounded mt-4">
          <h4 class="mb-3 text-center text-primary">✏️ Create a New Article</h4>
          <div class="form-group">
            <input type="text" class="form-control mt-2" name="title" placeholder="Input title here" required>
          </div>
          <div class="form-group">
            <textarea name="content" class="form-control mt-2" placeholder="Message as admin" required></textarea>
          </div>
          <div class="form-group">
            <input type="file" name="image" class="form-control-file mt-2">
          </div>
          <input type="submit" class="btn btn-primary form-control mt-3 mb-2" name="insertArticleBtn" value="🚀 Post Article">
        </form>


        <!-- List of active articles -->
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article): ?>
          <div class="card mt-4 shadow p-3">
          <div class="card-body">
          <h1>📖 <?= htmlspecialchars($article['title']); ?></h1>

          <?php if (!empty($article['is_admin']) && $article['is_admin'] == 1): ?>
            <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
          <?php endif; ?>

          <!-- Author, Date, and Category -->
          <small class="text-muted">
            <strong><?= htmlspecialchars($article['username']); ?></strong> - <?= htmlspecialchars($article['created_at']); ?> 
            Category: <strong><?= htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?></strong>
          </small>

          <?php if (!empty($article['image_path'])): ?>
            <div class="text-center w-100">
              <img src="../uploads/<?= htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded shadow-sm" alt="Article Image">
            </div>
          <?php endif; ?>
          <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>
        </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  </div>
  <footer>
    School Newspaper System 
  </footer>
</body>
</html>
