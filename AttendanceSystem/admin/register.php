<!doctype html>
<html>
<head>
  <title>Admin Register</title>
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
  <h2>Register as Admin</h2>
  <?php if(!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  
  <form method="post" action="core/handleForms.php">
    <input type="hidden" name="action" value="register">
    <input type="hidden" name="role" value="admin"> 
    <div class="mb-2">
      <input class="form-control" name="name" placeholder="Full name" required>
    </div>
    <div class="mb-2">
      <input class="form-control" name="email" placeholder="Email" type="email" required>
    </div>
    <div class="mb-2">
      <input class="form-control" name="password" placeholder="Password" type="password" required>
    </div>
    
    <button class="btn btn-nude">Register Admin</button>
  </form>
  <hr>
  <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>
