<?php
session_start();
require_once __DIR__ . "/classes/User.php";
$user = new User();

if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf9; 
      color: #5c4b3c; 
    }
        .container {
            background: #fff6f0; 
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(92, 75, 60, 0.1); 
            margin-top: 50px;
        }

        h1 {
            font-weight: 600;
            color: #5c4b3c;
        }

        .lead {
            font-size: 1.1rem;
            color: #8b7b6a; 
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h1>
        <p class="lead">This is your Admin Dashboard.</p>
    </div>
</body>
</html>