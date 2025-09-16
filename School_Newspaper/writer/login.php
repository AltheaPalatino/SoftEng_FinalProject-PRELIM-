<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Writer Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" 
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
      body {
        font-family: "Comic Sans MS", "Arial Rounded MT Bold", Arial, sans-serif;
        background: linear-gradient(135deg, #87cefa, #ffb6c1);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .card {
        border-radius: 20px;
        border: none;
        box-shadow: 0px 6px 15px rgba(0,0,0,0.2);
      }

      .card-header {
        background: #ff6b81;
        color: white;
        text-align: center;
        font-weight: bold;
        font-size: 1.5rem;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
      }

      .form-control {
        border-radius: 12px;
      }

      .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 25px;
        font-weight: bold;
        padding: 10px 20px;
      }
      .btn-primary:hover {
        background-color: #0056b3;
      }

      a {
        color: #d63384;
        font-weight: bold;
      }
      a:hover {
        text-decoration: underline;
        color: #ff477e;
      }

      .mascot {
        max-width: 90px;
        margin-bottom: 10px;
      }

      .register-note {
        margin-top: 15px;
        font-size: 0.95rem;
        color: #333;
      }
    </style>
    <title>Writer Login</title>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 p-5">
          <div class="card shadow">
            <div class="card-header">
              <img src="https://cdn-icons-png.flaticon.com/512/201/201818.png" alt="Book Mascot" class="mascot">
              <h2><i class="bi bi-pencil"></i> Writer's Dashboard Login</h2>
            </div>
            <form action="core/handleForms.php" method="POST">
              <div class="card-body">
                <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  if ($_SESSION['status'] == "200") {
                    echo "<h5 style='color: green;'>{$_SESSION['message']}</h5>";
                  } else {
                    echo "<h5 style='color: red;'>{$_SESSION['message']}</h5>"; 
                  }
                }
                unset($_SESSION['message']);
                unset($_SESSION['status']);
                ?>
                <div class="form-group">
                  <label><i class="bi bi-envelope"></i> Email</label>
                  <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                  <label><i class="bi bi-lock"></i> Password</label>
                  <input type="password" class="form-control" name="password" required>
                  <input type="submit" class="btn btn-primary float-right mt-4" name="loginUserBtn" value="Login">
                </div>
                <p class="register-note">
                  <i class="bi bi-person-plus"></i> Don't have an account yet? 
                  <a href="register.php">Register here!</a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
