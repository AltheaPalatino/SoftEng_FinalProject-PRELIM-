<?php
require_once "../core/dbConfig.php"; 
require_once "../core/models.php"; 

$excuses = getAllExcuseLetters($pdo);
?>

<!doctype html>
<html>
<head>
  <title>Manage Excuses</title>
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

    /* Back button */
    .btn-nude1 {
      background-color: #d8c3b0;
      border: none;
      margin-bottom: 20px;
      padding: 8px 18px;
      border-radius: 8px;
      color: #5a3e36;
      font-weight: 600;
      transition: all 0.2s ease-in-out;
    }
    .btn-nude1:hover {
      background-color: #b89f8f;
      color: #fff;
    }

    /* Action buttons */
    .btn-approve {
      background-color: #a6d6a6; /* soft green */
      border: none;
      border-radius: 8px;
      padding: 6px 14px;
      font-weight: 500;
      color: #2d4a2d;
      transition: all 0.2s ease-in-out;
    }
    .btn-approve:hover {
      background-color: #7ea16e;
      color: #fff;
    }

    .btn-reject {
      background-color: #e8a1a1; /* soft red */
      border: none;
      border-radius: 8px;
      padding: 6px 14px;
      font-weight: 500;
      color: #5a3e36;
      transition: all 0.2s ease-in-out;
    }
    .btn-reject:hover {
      background-color: #b85f52;
      color: #fff;
    }

    /* Status badges */
    .badge-pending {
      background-color: #ffd98e;
      color: #5a3e36;
      border-radius: 8px;
      padding: 6px 12px;
      font-weight: 600;
    }
    .badge-approved {
      background-color: #a6d6a6;
      color: #2d4a2d;
      border-radius: 8px;
      padding: 6px 12px;
      font-weight: 600;
    }
    .badge-rejected {
      background-color: #e8a1a1;
      color: #5a3e36;
      border-radius: 8px;
      padding: 6px 12px;
      font-weight: 600;
    }

    /* Table */
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

    /* Alerts */
    .alert-success {
      background-color: #e2f3e9;
      color: #35644a;
      border: none;
      border-radius: 8px;
    }
    .alert-danger {
      background-color: #f8d7da;
      color: #842029;
      border: none;
      border-radius: 8px;
    }
  </style>
</head>
<body class="container py-4">

  <!-- Back button -->
  <button type="button" class="btn btn-nude1" onclick="window.history.back();">← Back</button>
  
  <h2>Manage Excuses</h2>

  <?php if(!empty($_SESSION['success'])){ echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); } ?>
  <?php if(!empty($_SESSION['error'])){ echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); } ?>

  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Student Name</th>
        <th>Reason</th>
        <th>File</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($excuses as $e): ?>
        <tr>
          <td><?=htmlspecialchars($e['student_name'])?></td>
          <td><?=htmlspecialchars($e['reason'])?></td>
          <td>
            <?php if($e['file_path']): ?>
              <a href="<?=$e['file_path']?>" target="_blank">📎 View File</a>
            <?php else: ?>
              No File
            <?php endif; ?>
          </td>
          <td>
            <?php if ($e['status'] == 'pending'): ?>
              <span class="badge-pending">Pending</span>
            <?php elseif ($e['status'] == 'approved'): ?>
              <span class="badge-approved">Approved</span>
            <?php else: ?>
              <span class="badge-rejected">Rejected</span>
            <?php endif; ?>
          </td>
          <td>
            <form method="POST" action="../core/handleForms.php" style="display:inline-block">
              <input type="hidden" name="action" value="update_excuse_status">
              <input type="hidden" name="excuse_id" value="<?=$e['id']?>">
              <button name="status" value="approved" class="btn-approve btn-sm">Approve</button>
              <button name="status" value="rejected" class="btn-reject btn-sm">Reject</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
