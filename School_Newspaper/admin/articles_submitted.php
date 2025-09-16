<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
  exit;
}  
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Article Submitted</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/articles_submitted.css"> 
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <!-- Article submission form -->
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="p-4 shadow-lg bg-white rounded mt-4">
          <h2 class="text-center text-primary mb-4">📝 Write Your Article</h2>
          <div class="form-group">
            <input type="text" class="form-control" name="title" placeholder="🎨 Input title here" required>
          </div>
          <div class="form-group">
            <textarea name="content" class="form-control" placeholder="📖 Share your story..." required></textarea>
          </div>
          <div class="form-group">
            <label>Upload Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="form-control-file">
          </div>
          <input type="submit" class="btn btn-primary form-control mt-3 mb-2" name="insertArticleBtn" value="🚀 Submit Article">
        </form>

        <div class="display-4">Double click to edit ✨</div>
        <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>

        <?php foreach ($articles as $article): ?>
          <div class="card mt-4 shadow articleCard">
            <div class="card-body">
              <h1><?= htmlspecialchars($article['title']); ?></h1> 
              <small><?= htmlspecialchars($article['username']); ?> - <?= $article['created_at']; ?></small>
              
              <?php if (!empty($article['image_path'])): ?>
                <div class="text-center w-100">
                  <img src="../uploads/<?php echo htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded shadow" alt="Article Image">
                </div>
              <?php endif; ?>

              <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>

              <!-- Delete form -->
              <form class="deleteArticleForm">
                <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>" class="article_id">
                <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="🗑 Delete">
              </form>

              <!-- Update form -->
              <div class="updateArticleForm d-none">
                <h4>Edit the Article ✏️</h4>
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group mt-4">
                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($article['title']); ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="content" class="form-control"><?= htmlspecialchars($article['content']); ?></textarea>
                    <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>">
                  </div>
                  <div class="form-group">
                    <label>Change Image (optional)</label>
                    <input type="file" name="image" accept="image/*" class="form-control-file">
                  </div>
                  <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn" value="💾 Update Article">
                </form>
              </div>
            </div>
          </div>  
        <?php endforeach; ?> 
      </div>
    </div>
  </div>

  <script>
    $('.articleCard').on('dblclick', function () {
      var updateArticleForm = $(this).find('.updateArticleForm');
      updateArticleForm.toggleClass('d-none');
    });

    $('.deleteArticleForm').on('submit', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).find('.article_id').val(),
        deleteArticleBtn: 1
      }
      if (confirm("Are you sure you want to delete this article?")) {
        $.ajax({
          type:"POST",
          url: "core/handleForms.php",
          data:formData,
          success: function (data) {
            if (data) {
              location.reload();
            } else {
              alert("Deletion failed");
            }
          }
        })
      }
    })
  </script>
</body>
</html>
