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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf9; 
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

    .card {
      background-color: #fff6f0; 
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1);
    }

    .card-body h3 {
      color: #5c4b3c;
      margin-bottom: 10px;
    }

    .form-control[disabled], textarea[disabled] {
      background-color: #f5ebe0;
      color: #5c4b3c;
      border: 1px solid #d8c3a5;
    }

    img.img-fluid {
      border-radius: 8px;
      border: 1px solid #d8c3a5;
      max-width: 250px;
    }

    textarea.form-control {
      min-height: 120px;
    }

    @media (max-width: 768px) {
      .card-body .row > .col-md-6 {
        margin-bottom: 20px;
      }
      img.img-fluid {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <?php $userInfo = $userObj->getUsers($_GET['user_id']); ?>
  <div class="container-fluid">
    <div class="display-4 text-center">Hello there and welcome!</div>
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 text-center">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" 
                     class="img-fluid mt-4 mb-4" 
                     alt="Profile Picture">
                <h3>Username: <?= htmlspecialchars($userInfo['username']); ?></h3>
                <h3>Email: <?= htmlspecialchars($userInfo['email']); ?></h3>
                <h3>Phone Number: <?= htmlspecialchars($userInfo['contact_number']); ?></h3>
              </div>
              <div class="col-md-6">
                <form>
                  <div class="card-body">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" value="<?= htmlspecialchars($userInfo['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" value="<?= htmlspecialchars($userInfo['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Contact Number</label>
                      <input type="text" class="form-control" value="<?= htmlspecialchars($userInfo['contact_number']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Bio</label>
                      <textarea class="form-control" disabled><?= htmlspecialchars($userInfo['bio_description']); ?></textarea>
                    </div>  
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
