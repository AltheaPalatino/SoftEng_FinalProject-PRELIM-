<?php 
require_once 'classloader.php'; 

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($userObj->isAdmin()) {
    header("Location: ../client/index.php");
    exit;
}

require_once 'classes/Category.php'; 
$categoryObj = new Category();

// Load categories & subcategories
$categories = $categoryObj->getCategoriesWithSubcategories();
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
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf9; 
      color: #5c4b3c; 
    }

    .container-fluid {
      padding: 40px 30px;
    }

    .display-4 {
      font-weight: 600;
      color: #5c4b3c;
      margin-bottom: 30px;
      text-align: center;
    }

    .card {
      background-color: #fff6f0; 
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1);
    }

    .card h3, .card h4, .card h5, .card p {
      color: #5c4b3c;
    }

    .form-control {
      background-color: #f5ebe0;
      border: 1px solid #d8c3a5;
      color: #5c4b3c;
    }

    .form-control:focus {
      background-color: #fff;
      border-color: #b89c7a;
      box-shadow: 0 0 4px rgba(184,156,122,0.5);
    }

    .btn-primary {
      background-color: #c9b89d;
      border-color: #c9b89d;
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #a89274;
      border-color: #a89274;
    }

    img.img-fluid {
      border-radius: 8px;
      border: 1px solid #d8c3a5;
      max-height: 400px;
      object-fit: cover;
    }

    .float-right a {
      color: #a89274;
      text-decoration: none;
    }

    .float-right a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .row > .col-md-5, .row > .col-md-7 {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4">
      Hello there and welcome! 
      <span class="text-success"><?= htmlspecialchars($_SESSION['username']); ?></span>. Add Proposal Here!
    </div>

    <div class="row">
      <!-- Form Column -->
      <div class="col-md-5">
        <div class="card mt-4 mb-4 p-4">
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <?php  
            if (isset($_SESSION['message'], $_SESSION['status'])) {
              $color = ($_SESSION['status'] == "200") ? "green" : "red";
              echo "<h5 style='color: {$color};'>{$_SESSION['message']}</h5>";
              unset($_SESSION['message'], $_SESSION['status']);
            }
            ?>
            <h3 class="mb-4">Add Proposal Here!</h3>
            
            <div class="form-group">
              <label>Description</label>
              <input type="text" class="form-control" name="description" required>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Minimum Price</label>
                <input type="number" class="form-control" name="min_price" required>
              </div>
              <div class="form-group col-md-6">
                <label>Maximum Price</label>
                <input type="number" class="form-control" name="max_price" required>
              </div>
            </div>

            <div class="form-group">
              <label>Category</label>
              <select name="category_id" class="form-control" id="categoryDropdown" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Subcategory</label>
              <select name="subcategory_id" class="form-control" id="subcategoryDropdown" required>
                <option value="">-- Select Subcategory --</option>
                <?php foreach ($categories as $cat): ?>
                  <?php foreach ($cat['subcategories'] as $sub): ?>
                    <option value="<?= $sub['id'] ?>" data-category="<?= $cat['id'] ?>">
                      <?= htmlspecialchars($sub['name']) ?> (<?= $cat['name'] ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Image</label>
              <input type="file" class="form-control" name="image" required>
            </div>

            <input type="submit" class="btn btn-primary float-right mt-2" name="insertNewProposalBtn">
          </form>
        </div>
      </div>

      <!-- Proposals Column -->
      <div class="col-md-7">
        <?php 
        $getProposals = $proposalObj->getProposals(); 
        foreach ($getProposals as $proposal): 
        ?>
          <div class="card shadow mt-4 mb-4 p-3">
            <h4>
              <a href="other_profile_view.php?user_id=<?= $proposal['user_id']; ?>">
                <?= htmlspecialchars($proposal['username']); ?>
              </a>
            </h4>
            <img src="<?= '../images/' . $proposal['image']; ?>" alt="Proposal Image" class="img-fluid mb-3 w-100">
            <p class="mt-2"><i><?= $proposal['proposals_date_added']; ?></i></p>
            <p><?= htmlspecialchars($proposal['description']); ?></p>
            <h5><i><?= number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h5>
            <div class="float-right">
              <a href="#">Check out services</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#categoryDropdown').on('change', function() {
        var categoryId = $(this).val();
        $('#subcategoryDropdown option').each(function() {
          var cat = $(this).data('category');
          if(!cat || cat == categoryId){
            $(this).show();
          } else {
            $(this).hide();
          }
        });
        $('#subcategoryDropdown').val('');
      });
    });
  </script>
</body>
</html>
