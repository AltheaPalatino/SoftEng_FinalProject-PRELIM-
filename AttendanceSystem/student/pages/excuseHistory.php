<?php
require_once "../core/dbConfig.php";
require_once "../core/models.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$excuses = getExcuseHistory($pdo, $student_id);
?>

<!doctype html>
<html>
<head>
  <title>My Excuse Letter History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { 
      background-color: #fdfaf6; 
      font-family: Arial, sans-serif;
    }
    h2 { 
      color: #5a3e36; 
      margin-bottom: 20px;
      font-weight: bold;
    }

    .btn-secondary {
      background-color: #d8c3b0;
      border: none;
      margin-bottom: 20px;
    }
    .btn-secondary:hover {
      background-color: #b89f8f;
    }

    .table {
      background-color: #f8eadd;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }
    .table th {
      background-color: #c89f94;
      color: white;
      text-align: center;
      vertical-align: middle;
    }
    .table td {
      vertical-align: middle;
      color: #5a3e36;
    }
    .table a {
      color: #5a3e36;
      text-decoration: none;
      font-weight: 500;
    }
    .table a:hover {
      text-decoration: underline;
      color: #a77b71;
    }

    /* Status Badges */
    .badge.bg-warning {
      background-color: #f1c27d !important;
      color: #5a3e36 !important;
    }
    .badge.bg-success {
      background-color: #9abf88 !important;
    }
    .badge.bg-danger {
      background-color: #d97b6c !important;
    }
  </style>
</head>
<body class="container py-4">

  <button type="button" class="btn btn-secondary" onclick="window.history.back();">Back</button>
  <h2>My Excuse Letter History</h2>

  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Reason</th>
        <th>File</th>
        <th>Status</th>
        <th>Date Submitted</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($excuses)): ?>
        <?php foreach ($excuses as $e): ?>
          <tr>
            <td><?=htmlspecialchars($e['reason'])?></td>
            <td>
              <?php if($e['file_path']): ?>
                <a href="<?=$e['file_path']?>" target="_blank">View File</a>
              <?php else: ?>
                No File
              <?php endif; ?>
            </td>
            <td>
              <?php if ($e['status'] == 'pending'): ?>
                <span class="badge bg-warning">Pending</span>
              <?php elseif ($e['status'] == 'approved'): ?>
                <span class="badge bg-success">Approved</span>
              <?php else: ?>
                <span class="badge bg-danger">Rejected</span>
              <?php endif; ?>
            </td>
            <td><?=htmlspecialchars($e['created_at'])?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center">No excuse letters submitted yet.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
