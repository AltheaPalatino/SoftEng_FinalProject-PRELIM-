<?php 
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$article_id = $_GET['id'] ?? 0;

if (!$articleObj->writerHasAccess($article_id, $_SESSION['user_id'])) {
    die("You do not have permission to edit this article.");
}

$article = $articleObj->getArticleById($article_id);

if (!$article) {
    die("Article not found.");
}

if (isset($_POST['editArticleBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $articleObj->updateArticleContent($article_id, $title, $content);
    header("Location: shared_articles.php?success=updated");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> Writer Edit Article</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Bootstrap CSS (for navbar compatibility) -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/edit_article.css"> 

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container mx-auto mt-6">
    <div class="page-title mb-4">
      <span><i class="bi bi-pencil-square"></i> Edit Article</span>
    </div>

    <div class="form-container">
      <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        
        <div>
          <label><i class="bi bi-type"></i> Title</label>
          <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" 
                 class="w-full p-2" required>
        </div>

        <div>
          <label><i class="bi bi-file-text"></i> Content</label>
          <textarea name="content" rows="6" 
                    class="w-full p-2" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <?php if (!empty($article['image_path'])): ?>
          <div>
            <label><i class="bi bi-image"></i> Current Image</label>
            <img src="<?= "../" . htmlspecialchars($article['image_path']) ?>" alt="Article Image" class="max-w-xs rounded mb-3 border">
          </div>
        <?php endif; ?>

        <div>
          <label><i class="bi bi-upload"></i> Change Image</label>
          <input type="file" name="image" accept="image/*" class="w-full p-2">
        </div>

        <button type="submit" name="editArticleBtn" class="btn-save text-white">
          <i class="bi bi-check-circle"></i> Save Changes
        </button>
      </form>
    </div>
  </div>

</body>
</html>
