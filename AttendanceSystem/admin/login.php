<!doctype html>
<html>
<head>
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .btn-nude {
      background-color: #E6D5B8;
      color: #5C4033;
      border: none;
    }
    .btn-nude:hover {
      background-color: #D2B48C;
      color: #fff;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h2>Login</h2>
  <?php if(!empty($_SESSION['error'])){ echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); } ?>
 <form method="post" action="/AttendanceSystem/Admin/core/handleForms.php">

    <input type="hidden" name="action" value="login">
    <div class="mb-2"><input class="form-control" name="email" placeholder="Email" type="email" required></div>
    <div class="mb-2"><input class="form-control" name="password" placeholder="Password" type="password" required></div>
    <button class="btn btn-nude">Login</button>
  </form>
  <hr>
  <a href="register.php">Register</a>
</div>
</body>
</html>
