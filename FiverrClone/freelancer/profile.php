<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
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
      font-family: "Arial", sans-serif;
      background-color: #fffaf3; 
      color: #5c4b3c; 
    }

    .display-4 {
      font-weight: 600;
      margin-top: 20px;
      margin-bottom: 30px;
      color: #5c4b3c;
    }

    .card {
      background-color: #fff6f0; 
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(92, 75, 60, 0.1);
      padding: 20px;
    }

    h3 {
      margin-bottom: 10px;
    }

    .img-fluid {
      border-radius: 10px;
      border: 1px solid #d8c3a5;
      width: auto;      
      height: auto;    
      max-width: 100%;  
      display: block;
      margin-bottom: 20px;
    }

    .form-control[disabled], textarea[disabled] {
      background-color: #fdf6f0; 
      color: #5c4b3c;
      border: 1px solid #d8c3a5;
    }

    label {
      font-weight: 500;
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
  <?php $userInfo = $userObj->getUsers($_SESSION['user_id']); ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Hello there and welcome!</div>
    <div class="text-center">
      <?php  
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
            $color = ($_SESSION['status'] == "200") ? "green" : "red";
            echo "<h5 style='color: {$color};'>{$_SESSION['message']}</h5>";
            unset($_SESSION['message'], $_SESSION['status']);
        }
      ?>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 text-center">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" 
                     class="img-fluid mt-4 mb-4" alt="Profile Picture">
                <h3>Username: <?= htmlspecialchars($userInfo['username']); ?></h3>
                <h3>Email: <?= htmlspecialchars($userInfo['email']); ?></h3>
                <h3>Phone Number: <?= htmlspecialchars($userInfo['contact_number']); ?></h3>
              </div>

              <div class="col-md-6">
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($userInfo['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($userInfo['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Contact Number</label>
                      <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($userInfo['contact_number']); ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Bio</label>
                      <textarea name="bio_description" class="form-control"><?= htmlspecialchars($userInfo['bio_description']); ?></textarea>
                    </div>
                    <div class="form-group">
                      <label>Display Picture</label>
                      <input type="file" class="form-control" name="display_picture">
                    </div>
                    <input type="submit" class="mt-4 btn btn-primary float-right" name="updateUserBtn">
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
