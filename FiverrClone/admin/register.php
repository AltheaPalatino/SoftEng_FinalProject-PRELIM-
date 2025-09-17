<?php
session_start();
require_once __DIR__ . "/classes/User.php";
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if ($user->registerAdmin($username, $email, $password)) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Registration failed!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <div class="container">
        <h2>Register Admin</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Register</button>
            <a href="login.php" class="btn btn-link">Login</a>
        </form>
    </div>
</body>
</html>
