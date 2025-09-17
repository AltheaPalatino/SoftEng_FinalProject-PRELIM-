<?php
require_once 'classloader.php';
require_once __DIR__ . '/../admin/classes/Category.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$catObj = new Category();

// Get selected subcategory
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

// Fetch proposals for this subcategory
$proposals = [];
if ($subcategory_id > 0) {
    $sql = "SELECT p.*, u.username 
            FROM proposals p
            JOIN fiverr_clone_users u ON p.user_id = u.user_id
            WHERE p.subcategory_id = :sub_id
            ORDER BY p.date_added DESC";

    $stmt = $catObj->getPdo()->prepare($sql);
    $stmt->execute(['sub_id' => $subcategory_id]);
    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Proposals</title>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
    body {
      background-color: #fffaf3; 
      font-family: Arial, sans-serif;
      color: #5c4b3c;
    }

    .card {
      background-color: #fff6f0;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(92, 75, 60, 0.1);
    }

    .card-img-top {
      width: auto;       
      height: auto;      
      max-width: 100%;    
      display: block;
      margin: 0 auto 15px; 
      border-radius: 10px;
      border: 1px solid #d8c3a5;
    }

    .card-title {
      color: #5c4b3c;
      font-weight: 600;
    }

    .card-text, p {
      color: #5c4b3c;
    }

    .container h2 {
      color: #5c4b3c;
      font-weight: 600;
      margin-bottom: 30px;
    }

    @media (max-width: 768px) {
      .card-img-top {
        width: 100%; 
        height: auto;
      }
    }
  </style>
</head>
<body>
      <?php include 'includes/navbar.php'; ?>
<div class="container my-5">
    <h2 class="mb-4">Proposals</h2>

    <?php if (!empty($proposals)): ?>
        <div class="row">
            <?php foreach ($proposals as $proposal): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <?php if (!empty($proposal['image'])): ?>
                            <img src="../images/<?php echo htmlspecialchars($proposal['image']); ?>" 
                                 class="card-img-top" 
                                 alt="Proposal Image" 
                                 style="max-height:200px; object-fit:cover;">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x200?text=No+Image" 
                                 class="card-img-top" 
                                 alt="No Image">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($proposal['username']); ?>
                            </h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars($proposal['description'])); ?>
                            </p>
                            <p><strong>Price:</strong> ₱<?php echo number_format($proposal['min_price']); ?> - ₱<?php echo number_format($proposal['max_price']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No proposals found.</div>
    <?php endif; ?>
</div>
</body>
</html>
