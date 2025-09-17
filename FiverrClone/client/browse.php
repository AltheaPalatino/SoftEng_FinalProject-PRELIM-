<?php
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get selected subcategory
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

$proposals = [];

if ($subcategory_id > 0) {
    $sql = "SELECT p.*, u.username 
            FROM proposals p
            JOIN fiverr_clone_users u ON p.user_id = u.user_id
            WHERE p.subcategory_id = :sub_id";

    $params = ['sub_id' => $subcategory_id];

    if (!empty($search)) {
        $sql .= " AND p.description LIKE :search";
        $params['search'] = "%$search%";
    }

    if ($min_price > 0) {
        $sql .= " AND p.min_price >= :min_price";
        $params['min_price'] = $min_price;
    }
    if ($max_price > 0) {
        $sql .= " AND p.max_price <= :max_price";
        $params['max_price'] = $max_price;
    }

    $sql .= " ORDER BY p.date_added DESC";

    $stmt = $userObj->getPdo()->prepare($sql);
    $stmt->execute($params);
    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Proposals</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdf6f0; 
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
        }

        .card {
            border: none;
            border-radius: 8px;
            background-color: #f5ebe0; 
            box-shadow: 0 2px 8px rgba(92, 75, 60, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(92, 75, 60, 0.15);
        }

        .card-body p {
            color: #5c4b3c;
        }

        .text-muted {
            color: #8b7b6a !important;
        }

        img.card-img-top {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .alert-info {
            background-color: #f0e0d0;
            color: #5c4b3c;
            border: none;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Browse Proposals</h2>
    <?php if (!empty($proposals)): ?>
        <div class="row">
            <?php foreach ($proposals as $p): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($p['image'])): ?>
                            <img src="../images/<?= htmlspecialchars($p['image']); ?>" 
                                 class="card-img-top" 
                                 alt="Proposal Image" 
                                 style="max-height:200px; object-fit:cover;">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x200?text=No+Image" 
                                 class="card-img-top" 
                                 alt="No Image">
                        <?php endif; ?>

                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                            <p><strong>Price:</strong> ₱<?= number_format($p['min_price']) ?> - ₱<?= number_format($p['max_price']) ?></p>
                            <p class="text-muted mb-0">By <?= htmlspecialchars($p['username']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No proposals found for this subcategory.</div>
    <?php endif; ?>
</div>
</body>
</html>
