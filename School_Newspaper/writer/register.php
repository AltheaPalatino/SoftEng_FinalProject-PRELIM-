<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Writer Register</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" 
        crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    body {
      font-family: "Comic Sans MS", "Arial Rounded MT Bold", Arial, sans-serif;
      background-image: url("https://img.freepik.com/free-vector/winter-blue-pink-gradient-background-vector_53876-117275.jpg");
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border-radius: 20px;
      border: none;
      box-shadow: 0px 6px 15px rgba(0,0,0,0.25);
    }

    .card-header {
      background: linear-gradient(90deg, #007bff, #ff4d6d, #ff99cc);
      color: white;
      text-align: center;
      font-weight: bold;
      font-size: 1.4rem;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .form-control {
      border-radius: 12px;
      padding: 10px;
      font-size: 1rem;
    }

    .btn-primary {
      background: #007bff;
      border: none;
      border-radius: 25px;
      font-weight: bold;
      padding: 10px 20px;
      transition: 0.3s ease;
    }
    .btn-primary:hover {
      background: #ff4d6d;
      transform: scale(1.05);
    }

    label {
      font-weight: 600;
      color: #333;
    }

    .mascot {
      max-width: 80px;
      margin-right: 10px;
    }

    .title-box {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>

  <title>Writer Registration</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 p-5">
        <div class="card shadow">
          <div class="card-header">
            <div class="title-box">
              <img src="https://cdn-icons-png.flaticon.com/512/201/201818.png" 
                   alt="Book Mascot" class="mascot">
              <span>Register Now as a Writer ✏️</span>
            </div>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body">
              <div class="form-group">
                <label><i class="bi bi-person-circle"></i> Username</label>
                <input type="text" class="form-control" name="username" required>
              </div>
              <div class="form-group">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="form-group">
                <label><i class="bi bi-lock"></i> Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <div class="form-group">
                <label><i class="bi bi-shield-lock"></i> Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
                <input type="submit" class="btn btn-primary float-right mt-4" 
                       name="insertNewUserBtn" value="Register">
              </div>
                <p class="register-note">
                  <i class="bi bi-person-plus"></i> You already have an account? 
                  <a href="login.php">Login here!</a>
                </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
