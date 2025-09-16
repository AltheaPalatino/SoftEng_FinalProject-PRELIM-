<?php 
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$sharedArticles = $articleObj->getSharedArticles($_SESSION['user_id']);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Writer Shared Articles</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/shared_articles.css"> 
</head>
<body>

  <!-- Include Navbar -->
  <?php include 'includes/navbar.php'; ?>

  <div class="container mx-auto mt-6">
    <div class="flex items-center mb-6">
      <h1 class="text-3xl font-bold"><i class="bi bi-journal-text"></i> Shared Articles</h1>
    </div>

    <?php if (count($sharedArticles) > 0): ?>
      <div class="space-y-4">
        <?php foreach ($sharedArticles as $article): ?>
          <div class="article-card shadow p-4">
            <h2 class="article-title"><i class="bi bi-book"></i> <?= htmlspecialchars($article['title']) ?></h2>
            <p class="article-meta mb-2">
              <i class="bi bi-person-circle text-blue-500"></i> 
              <?= htmlspecialchars($article['author_name']) ?> | 
              <i class="bi bi-calendar3 text-pink-500"></i> <?= $article['created_at'] ?>
            </p>
            <p class="mb-2"><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))) ?>...</p>
            
            <?php if ($article['image_path']): ?>
              <img src="<?= "../" . htmlspecialchars($article['image_path']) ?>" alt="Article Image" class="max-w-xs mt-2 rounded shadow">
            <?php endif; ?>

            <div class="mt-3">
              <a href="edit_article.php?id=<?= $article['article_id'] ?>" class="edit-link">
                <i class="bi bi-pencil-square"></i> Edit Article
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-gray-600 italic">No shared articles available.</p>
    <?php endif; ?>
  </div>

</body>
</html>
