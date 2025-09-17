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
      background-color: #fffdf9; 
      color: #5c4b3c; 
    }

    .container-fluid {
      padding: 50px 30px;
    }

    .display-4 {
      font-weight: 600;
      color: #5c4b3c;
      margin-bottom: 30px;
      text-align: center;
    }

    .welcome-card {
      background-color: #fff6f0; 
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1);
      padding: 40px;
      text-align: center;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="welcome-card">
      <div class="display-4">
        Hello there and welcome! Here are all the submitted project offers!
      </div>
    </div>
  </div>
</body>
</html>
