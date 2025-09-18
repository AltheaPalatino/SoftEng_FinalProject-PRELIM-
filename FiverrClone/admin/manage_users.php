<?php
session_start();
require_once __DIR__ . "/classes/User.php";

$userObj = new User();

// Restrict page to admins only
if (!$userObj->isLoggedIn() || $_SESSION['role'] !== 'fiverr_administrator') {
    header("Location: login.php");
    exit;
}

// Get PDO from User object
$pdo = $userObj->getPDO(); 

// Fetch all users
$users = $pdo->query("SELECT * FROM fiverr_clone_users")->fetchAll(PDO::FETCH_ASSOC);

// Handle role updates
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['updateRoleBtn'])) {
    $stmt = $pdo->prepare("UPDATE fiverr_clone_users SET role = :role WHERE user_id = :id");
    $stmt->execute([
        ':role' => $_POST['role'],
        ':id' => $_POST['user_id']
    ]);
    header("Location: manage_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
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

    h2 {
      font-weight: 600;
      color: #5c4b3c;
      margin-bottom: 20px;
    }

    table {
      background: #fff6f0;
      border-radius: 8px;
      overflow: hidden;
    }

    thead {
      background-color: #d8c3a5; 
      color: #5c4b3c;
    }

    th, td {
      vertical-align: middle !important;
    }

    tr:nth-child(even) {
      background-color: #f5ebe0;
    }

    tr:hover {
      background-color: #ecd9c6;
    }

    .btn-primary {
      background-color: #b89c7a; 
      border-color: #b89c7a;
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #a08563;
      border-color: #a08563;
    }

    select.form-control {
      min-width: 140px;
      background-color: #fff6f0;
      border: 1px solid #d8c3a5;
      color: #5c4b3c;
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
  <h2>Manage Users</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Username</th><th>Email</th><th>Role</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td>
            <form method="POST" class="form-inline">
              <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
              <select name="role" class="form-control mr-2">
                <option value="client" <?= $u['role']=='client'?'selected':'' ?>>Client</option>
                <option value="freelancer" <?= $u['role']=='freelancer'?'selected':'' ?>>Freelancer</option>
                <option value="fiverr_administrator" <?= $u['role']=='fiverr_administrator'?'selected':'' ?>>Administrator</option>
              </select>
              <button type="submit" name="updateRoleBtn" class="btn btn-primary btn-sm">Update</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
