<?php  
require_once 'classloader.php'; 
require_once 'classes/Category.php';

// Make sure database is available
$db = new Database();
$pdo = $db->getConnection();

// Initialize category object
$categoryObj = new Category($pdo);

// Fetch all categories created by admin
$categories = $categoryObj->getAllCategories();
?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
  exit;
}  
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Writer Submitted Articles</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/articles_submitted.css">
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <!-- Article Submission Form -->
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="mt-4 p-4 bg-white rounded shadow">
          <h3 class="mb-3 text-primary"><i class="bi bi-pencil-square"></i> Submit a New Article</h3>
          
          <div class="form-group">
            <label><i class="bi bi-type"></i> Title:</label>
            <input type="text" class="form-control" name="title" required>
          </div>

          <div class="form-group">
            <label><i class="bi bi-card-text"></i> Content:</label>
            <textarea name="content" class="form-control" required></textarea>
          </div>

          <div class="form-group">
            <label><i class="bi bi-image"></i> Upload Image:</label>
            <input type="file" class="form-control-file" name="image" accept="image/*">
          </div>

          <!-- HAVE CHANGES Category Dropdown -->
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

          <button type="submit" class="btn btn-primary form-control mt-3 mb-4" name="insertArticleBtn">
            <i class="bi bi-send-fill"></i> Submit Article
          </button>
        </form>

        <div class="display-4 text-center mt-4">
          Double click your article to edit
        </div>

        <!-- HAVE CHANGE WRITER'S OWN ARTICLES -->
        <?php 
        $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); 
        foreach ($articles as $article): 
        ?>
          <div class="card mt-4 shadow articleCard">
            <div class="card-body">
              <h2 class="h3 text-primary"><i class="bi bi-journal-text"></i> <?= htmlspecialchars($article['title']); ?></h2> 
              <small class="text-muted">
                <?= htmlspecialchars($article['username']); ?> - <?= htmlspecialchars($article['created_at']); ?> 
                | Category: <strong><?= htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?></strong>
              </small>

              <!-- Status Badge -->
              <?php if ($article['is_active'] == 0): ?>
                <p class="status-badge status-pending">PENDING</p>
              <?php else: ?>
                <p class="status-badge status-active">ACTIVE</p>
              <?php endif; ?>

              <!-- Article Image -->
              <?php if (!empty($article['image_path'])): ?>
                <div class="text-center w-100">
                  <img src="../uploads/<?= htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded" alt="Article Image">
                </div>
              <?php endif; ?>

              <!-- Article Content -->
              <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>

              <!-- Delete Form -->
              <form class="deleteArticleForm">
                <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>" class="article_id">
                <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="Delete">
              </form>

              <!-- Update Form (Double Click) -->
              <div class="updateArticleForm d-none mt-4">
                <h4 class="text-info"><i class="bi bi-pencil-fill"></i> Edit Article</h4>
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group mt-4">
                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($article['title']); ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="content" class="form-control"><?= htmlspecialchars($article['content']); ?></textarea>
                  </div>
                  <div class="form-group">
                    <label><i class="bi bi-image"></i> Change Image:</label>
                    <input type="file" class="form-control-file" name="image" accept="image/*">
                  </div>
                  <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>">
                  <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn" value="Save Changes">
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- HAVE CHANGE ALL SUBMITTED ARTICLES -->
        <div class="article-list-title"><i class="bi bi-collection"></i> All Submitted Articles</div>
        <?php 
        $allArticles = $articleObj->getAllArticles();
        foreach ($allArticles as $article): 
        ?>
          <div class="card mt-4 shadow">
            <div class="card-body">
              <h2 class="h4 text-danger"><i class="bi bi-journal-check"></i> <?= htmlspecialchars($article['title']); ?></h2>

              <p class="text-muted small">
                By <?= htmlspecialchars($article['username']); ?> | <?= htmlspecialchars($article['created_at']); ?> 
                | Category: <strong><?= htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?></strong>
              </p>

              <!-- Status Badge -->
              <?php if ($article['is_active'] == 0): ?>
                <p class="status-badge status-pending">PENDING</p>
              <?php else: ?>
                <p class="status-badge status-active">ACTIVE</p>
              <?php endif; ?>

              <!-- Article Image -->
              <?php if (!empty($article['image_path'])): ?>
                <div class="text-center w-100">
                  <img src="../uploads/<?= htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded" alt="Article Image">
                </div>
              <?php endif; ?>

              <!-- Content Snippet -->
              <p><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))); ?>...</p>

              <!-- Request Edit Form -->
              <form action="core/handleForms.php" method="POST" class="mt-2">
                <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>">
                <button type="submit" name="request_edit" class="btn btn-info">
                  <i class="bi bi-pencil-square"></i> Request Edit
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>


  <script>
    // Double click to toggle edit form
    $(document).on('dblclick', '.articleCard', function () {
      $(this).find('.updateArticleForm').toggleClass('d-none');
    });

    // Delete article AJAX
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
            if (data) {
              location.reload();
            } else {
              alert("Deletion failed");
            }
          }
        });
      }
    });
  </script>
</body>
</html>
