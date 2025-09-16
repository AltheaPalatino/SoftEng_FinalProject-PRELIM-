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

$requests = $articleObj->getEditRequestsForAuthor($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Articles From Students</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/articles_from_students.css">  
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <!-- Pending Articles Section -->
        <div class="section-title">Pending Articles</div>
        <?php 

        $articles = $articleObj->getArticles();
        foreach ($articles as $article) { 
        ?>
        <div class="card mt-4 shadow articleCard">
          <div class="card-body">
            <h1>📝 <?= htmlspecialchars($article['title']); ?></h1> 
            <small>
              <?= htmlspecialchars($article['username']); ?> • <?= htmlspecialchars($article['created_at']); ?> 
              | Category: <strong><?= htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?></strong>
            </small>

            <?php if ($article['is_active'] == 0) { ?>
              <p class="text-danger">Status: PENDING</p>
            <?php } ?>
            <?php if ($article['is_active'] == 1) { ?>
              <p class="text-success">Status: ACTIVE</p>
            <?php } ?>

            <?php if (!empty($article['image_path'])): ?>
              <div class="text-center w-100">
                <img src="../uploads/<?= htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded shadow-sm" alt="Article Image">
              </div>
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>

            <!-- Delete Form -->
            <form class="deleteArticleForm">
              <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
              <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="🗑 Delete">
            </form>

            <!-- Approve/Reject Status -->
            <form class="updateArticleStatus">
              <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
              <select name="is_active" class="form-control is_active_select mt-2" article_id=<?php echo $article['article_id']; ?>>
                <option value="">Select status</option>
                <option value="0">Pending</option>
                <option value="1">Active</option>
              </select>
            </form>

            <!-- Edit Form -->
            <div class="updateArticleForm d-none">
              <h4>✏️ Edit the article</h4>
              <form action="core/handleForms.php" method="POST">
                <div class="form-group mt-3">
                  <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                </div>
                <div class="form-group">
                  <textarea name="description" class="form-control"><?php echo $article['content']; ?></textarea>
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                  <input type="submit" class="btn btn-primary float-right mt-3" name="editArticleBtn" value="💾 Save Changes">
                </div>
              </form>
            </div>
          </div>
        </div>  
        <?php } ?> 

        <!-- Edit Requests Section -->
        <div class="section-title">✍️ Edit Requests for Your Articles</div>
        <?php if (count($requests) > 0): ?>
          <?php foreach ($requests as $req): ?>
            <div class="card mt-4 shadow">
              <div class="card-body">
                <h2 class="h5">📖 <?php echo htmlspecialchars($req['title']); ?></h2>
                <p class="text-muted small">
                  Requested by: <strong><?php echo htmlspecialchars($req['requester_name']); ?></strong> 
                  on <?php echo $req['requested_at']; ?>
                </p>
                <p>Status: <span class="font-weight-bold text-danger"><?php echo ucfirst($req['status']); ?></span></p>

                <?php if ($req['status'] === 'pending'): ?>
                  <form action="core/handleForms.php" method="POST" class="mt-2">
                    <input type="hidden" name="request_id" value="<?php echo $req['request_id']; ?>">
                    <button type="submit" name="accept_edit" class="btn btn-success">✔ Accept</button>
                    <button type="submit" name="reject_edit" class="btn btn-danger">✖ Reject</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted text-center">No edit requests yet. 🎉</p>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <footer>
    🌟 School Newspaper System 🎨
  </footer>

  <script>
    $('.is_active_select').on('change', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).attr('article_id'),
        status: $(this).val(),
        updateArticleVisibility:1
      }

      if (formData.article_id != "" && formData.status != "") {
        $.ajax({
          type:"POST",
          url: "core/handleForms.php",
          data:formData,
          success: function (data) {
            if (data) {
              location.reload();
            }
            else{
              alert("Visibility update failed");
            }
          }
        })
      }
    })

    // Delete article
    $('.deleteArticleForm').on('submit', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).find('.article_id').val(),
        deleteArticleBtn: 1
      };
      if (confirm("Are you sure you want to delete this article?")) {
        $.ajax({
          type: "POST",
          url: "core/handleForms.php",
          data: formData,
          success: function (data) {
            if (data == 1) {
              alert("Article deleted successfully.");
              location.reload();
            } else {
              alert("Deletion failed or unauthorized.");
            }
          },
          error: function() {
            alert("An error occurred while deleting the article.");
          }
        });
      }
    });
  </script>
</body>
</html>
