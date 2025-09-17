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
$categoryObj = new Category();
$categories = $categoryObj->getCategoriesWithSubcategories();

// Re-index categories by ID for easier lookup
$categoriesById = [];
foreach ($categories as $cat) {
    $categoriesById[$cat['id']] = $cat;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body { 
      font-family: Arial, sans-serif; 
      background-color: #fffdf9; 
      color: #5c4b3c; 
    }

    .display-4 {
      font-weight: 600;
      margin-bottom: 30px;
      text-align: center;
      color: #5c4b3c;
    }

    .card {
      background-color: #fff6f0; 
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1);
    }

    .card h2, .card h4, .card h5, .card p {
      color: #5c4b3c;
    }

    .offer {
      margin-bottom: 1rem;
      background-color: #fdf6f0; 
      padding: 10px 15px;
      border-radius: 8px;
    }

    .offer h4 {
      margin-bottom: 0.2rem;
    }

    .offer p {
      margin-bottom: 0.2rem;
    }

    .img-fluid {
      border-radius: 8px;
      border: 1px solid #d8c3a5;
      width: auto;       
      height: auto;     
      max-width: 100%;  
    }

    a {
      color: #a89274;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .float-right a {
      font-weight: 500;
    }

    .card-body.overflow-auto {
      max-height: 350px;
    }

    @media (max-width: 768px) {
      .row > .col-md-6 {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container-fluid">
  <div class="display-4 text-center">Hello there and welcome!</div>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <?php $getProposalsByUserID = $proposalObj->getProposalsByUserID($_SESSION['user_id']); ?>
      <?php foreach ($getProposalsByUserID as $proposal): 
        // Get category & subcategory names
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
      <div class="card shadow mt-4 mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h2><a href="#"><?php echo htmlspecialchars($proposal['username']); ?></a></h2>
              <img src="<?php echo '../images/'.$proposal['image']; ?>" class="img-fluid" alt="">
              <p class="mt-2"><strong>Category:</strong> <?php echo htmlspecialchars($categoryName); ?><br>
                 <strong>Subcategory:</strong> <?php echo htmlspecialchars($subcategoryName); ?></p>
              <p class="mt-2 mb-4"><?php echo htmlspecialchars($proposal['description']); ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              <div class="float-right">
                <a href="#">Check out services</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header"><h2>All Offers</h2></div>
                <div class="card-body overflow-auto">
                  <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                  <?php foreach ($getOffersByProposalID as $offer): ?>
                  <div class="offer">
                    <h4><?php echo htmlspecialchars($offer['username']); ?> 
                      <span class="text-primary">(<?php echo htmlspecialchars($offer['contact_number']); ?>)</span>
                    </h4>
                    <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                    <p><?php echo htmlspecialchars($offer['description']); ?></p>
                    <hr>
                  </div>
                  <?php endforeach; ?>
                  <?php if(empty($getOffersByProposalID)) echo "<p>No offers yet.</p>"; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($getProposalsByUserID)) echo "<p class='text-center'>You have no proposals yet.</p>"; ?>
    </div>
  </div>
</div>
</body>
</html>
