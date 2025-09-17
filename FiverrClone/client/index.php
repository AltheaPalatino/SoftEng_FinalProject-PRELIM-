<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../freelancer/index.php");
} 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf9; /
      color: #5c4b3c;
    }

    .container-fluid {
      padding: 30px;
    }

    .display-4 {
      font-weight: 600;
      color: #5c4b3c;
      margin-bottom: 30px;
    }

    .text-success {
      color: #4a7c59 !important;
    }

    h1 {
      font-size: 1.5rem;
    }

    .card {
      background-color: #fff6f0;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1);
      margin-bottom: 30px;
    }

    .card img {
      border-radius: 8px;
      width: 100%;      
      height: 600PX;      
      object-fit: unset; 
    }

    .card h2 {
      color: #5c4b3c;
      font-weight: 600;
    }

    .card p {
      color: #5c4b3c;
    }

    .card .card-body .offer {
      background-color: #f9f2e8; 
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      border: 1px solid #e6d6c3;
    }

    .card .card-body .offer h4 {
      font-weight: 500;
      color: #5c4b3c;
    }

    .card .card-body .offer small {
      color: #8b7b6a;
    }

    .form-control {
      background-color: #f5ebe0;
      border: 1px solid #d8c3a5;
      color: #5c4b3c;
    }

    .form-control:focus {
      background-color: #fff;
      border-color: #b89c7a;
      box-shadow: 0 0 4px rgba(184, 156, 122, 0.5);
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

    .btn-danger {
      background-color: #d98880;
      border-color: #d98880;
      color: #fff;
    }

    .btn-danger:hover {
      background-color: #c96f68;
      border-color: #c96f68;
    }

    .updateOfferForm {
      background-color: #fdf3e8;
      padding: 10px;
      border-radius: 8px;
      margin-top: 10px;
      border: 1px solid #e6d6c3;
    }

    .card .card-header {
      background-color: #d8c3a5;
      color: #3e2f21;
      font-weight: 500;
    }

    .card-footer {
      background-color: #f9f2e8;
      border-top: 1px solid #e6d6c3;
    }

    .card-body.overflow-auto {
      max-height: 500px;
      overflow-y: auto;
    }

  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="display-4 text-center">Hello there and welcome! <span class="text-success"><?= htmlspecialchars($_SESSION['username']); ?>.</span> Double click to edit your offers and then press enter to save!</div>

    <div class="text-center">
      <?php  
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
          $color = $_SESSION['status'] == "200" ? "green" : "red";
          echo "<h1 style='color: {$color};'>{$_SESSION['message']}</h1>";
        }
        unset($_SESSION['message']);
        unset($_SESSION['status']);
      ?>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-12">
        <?php $getProposals = $proposalObj->getProposals(); ?>
        <?php foreach ($getProposals as $proposal): ?>
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h2><a href="other_profile_view.php?user_id=<?= $proposal['user_id']; ?>"><?= htmlspecialchars($proposal['username']); ?></a></h2>
                <img src="<?= '../images/' . htmlspecialchars($proposal['image']); ?>" class="img-fluid" alt="">
                <p class="mt-4 mb-4"><?= htmlspecialchars($proposal['description']); ?></p>
                <h4><i><?= number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              </div>
              <div class="col-md-6">
                <div class="card" style="height: 600px;">
                  <div class="card-header"><h2>All Offers</h2></div>
                  <div class="card-body overflow-auto">
                    <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                    <?php foreach ($getOffersByProposalID as $offer): ?>
                    <div class="offer">
                      <h4><?= htmlspecialchars($offer['username']); ?> <span class="text-primary">( <?= htmlspecialchars($offer['contact_number']); ?> )</span></h4>
                      <small><i><?= htmlspecialchars($offer['offer_date_added']); ?></i></small>
                      <p><?= htmlspecialchars($offer['description']); ?></p>

                      <?php if ($offer['user_id'] == $_SESSION['user_id']): ?>
                        <form action="core/handleForms.php" method="POST">
                          <input type="hidden" value="<?= $offer['offer_id']; ?>" name="offer_id">
                          <input type="submit" class="btn btn-danger mt-2" value="Delete" name="deleteOfferBtn">
                        </form>

                        <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none">
                          <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($offer['description']); ?>" name="description">
                            <input type="hidden" value="<?= $offer['offer_id']; ?>" name="offer_id">
                            <input type="submit" class="btn btn-primary form-control mt-2" name="updateOfferBtn">
                          </div>
                        </form>
                      <?php endif; ?>
                      <hr>
                    </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="card-footer">
                    <form action="core/handleForms.php" method="POST">
                      <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description">
                        <input type="hidden" name="proposal_id" value="<?= $proposal['proposal_id']; ?>">
                        <input type="submit" class="btn btn-primary float-right mt-4" name="insertOfferBtn"> 
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script>
    $('.offer').on('dblclick', function () {
      $(this).find('.updateOfferForm').toggleClass('d-none');
    });
  </script>
</body>
</html>
