<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
  exit;
}  

// Load Category class
require_once __DIR__ . '/classes/Category.php';
$catObj = new Category();

// Handle new category/subcategory submission if coming from admin panel
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['addCategoryBtn']) && !empty($_POST['category_name'])) {
        $catObj->addCategory($_POST['category_name']);
    }
    if (isset($_POST['addSubcategoryBtn']) && !empty($_POST['subcategory_name'])) {
        $catObj->addSubcategory($_POST['category_id'], $_POST['subcategory_name']);
    }
}

//Reload categories after any addition/update
$categories = $catObj->getCategoriesWithSubcategories();
?>


<?php
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Load Category class
require_once 'classes/Category.php';
$categoryObj = new Category();
$categories = $categoryObj->getCategoriesWithSubcategories();

// Re-index categories array by ID for easier lookup
$categoriesById = [];
foreach ($categories as $cat) {
    $categoriesById[$cat['id']] = $cat;
}

// Get user proposals
$getProposalsByUserID = $proposalObj->getProposalsByUserID($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffaf3; 
            color: #5c4b3c; 
        }

        .display-4 {
            margin-top: 20px;
            margin-bottom: 30px;
            font-weight: 600;
            color: #5c4b3c;
        }

        .proposalCard {
            background-color: #fff6f0; 
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(92, 75, 60, 0.1);
            padding: 20px;
        }

        .proposalCard img {
            display: block;
            margin-bottom: 15px;
            width: auto;     
            height: auto;    
            max-width: 100%; 
            border-radius: 10px;
            border: 1px solid #d8c3a5;
        }

        h2, h4, h5 {
            color: #5c4b3c;
        }

        textarea, input.form-control {
            background-color: #fdf6f0;
            border: 1px solid #d8c3a5;
            color: #5c4b3c;
        }

        textarea {
            resize: vertical;
        }

        .btn-primary {
            background-color: #b28704; 
            border-color: #b28704;
        }

        .btn-danger {
            background-color: #d9534f;
            border-color: #d9534f;
        }

        .updateProposalForm {
            margin-top: 15px;
            padding: 15px;
            background-color: #fff3e0;
            border-radius: 10px;
            border: 1px solid #e0c9b0;
        }

        @media (max-width: 768px) {
            .proposalCard img {
                width: 100%; 
                height: auto;
            }
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="display-4 text-center">Double click to edit!</div>
    <div class="text-center">
        <?php  
        if (isset($_SESSION['message'], $_SESSION['status'])) {
            $color = ($_SESSION['status'] === "200") ? "green" : "red";
            echo "<h1 style='color: {$color};'>{$_SESSION['message']}</h1>";
            unset($_SESSION['message'], $_SESSION['status']);
        }
        ?>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php foreach ($getProposalsByUserID as $proposal): 
                $categoryName = $subcategoryName = '';
                if (isset($proposal['category_id'], $categoriesById[$proposal['category_id']])) {
                    $categoryName = $categoriesById[$proposal['category_id']]['name'];
                    foreach ($categoriesById[$proposal['category_id']]['subcategories'] as $sub) {
                        if ($sub['id'] == $proposal['subcategory_id']) {
                            $subcategoryName = $sub['name'];
                            break;
                        }
                    }
                }
            ?>
            <div class="card proposalCard shadow mt-4 mb-4">
                <div class="card-body">
                    <h2><a href="#"><?php echo htmlspecialchars($proposal['username']); ?></a></h2>
                    <img src="<?php echo "../images/".$proposal['image']; ?>" class="img-fluid" alt="">
                    <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
                    <p class="mt-2"><?php echo htmlspecialchars($proposal['description']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($categoryName); ?><br>
                       <strong>Subcategory:</strong> <?php echo htmlspecialchars($subcategoryName); ?></p>
                    <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?></i></h4>

                    <form action="core/handleForms.php" method="POST">
                        <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                        <input type="hidden" name="image" value="<?php echo $proposal['image']; ?>">
                        <input type="submit" name="deleteProposalBtn" class="btn btn-danger float-right" value="Delete">
                    </form>

                    <form action="core/handleForms.php" method="POST" class="updateProposalForm d-none">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Price</label>
                                    <input type="number" class="form-control" name="min_price" value="<?php echo $proposal['min_price']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Maximum Price</label>
                                    <input type="number" class="form-control" name="max_price" value="<?php echo $proposal['max_price']; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= ($proposal['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subcategory</label>
                                    <select name="subcategory_id" class="form-control" required>
                                        <option value="">-- Select Subcategory --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <?php foreach ($cat['subcategories'] as $sub): ?>
                                                <option value="<?= $sub['id'] ?>" <?= ($proposal['subcategory_id'] == $sub['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($sub['name']) ?> (<?= $cat['name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($proposal['description']); ?></textarea>
                                    <input type="submit" name="updateProposalBtn" class="btn btn-primary mt-2 form-control" value="Update">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
$('.proposalCard').on('dblclick', function () {
    var updateProposalForm = $(this).find('.updateProposalForm');
    updateProposalForm.toggleClass('d-none');
});
</script>
</body>
</html>
