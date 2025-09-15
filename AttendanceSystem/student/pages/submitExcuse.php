<!doctype html>
<html>
<head>
  <title>Submit Excuse</title>
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

    .btn-primary {
      background-color: #c89f94;
      border: none;
    }
    .btn-primary:hover {
      background-color: #a77b71;
    }

    .btn-secondary {
      background-color: #d8c3b0;
      border: none;
    }
    .btn-secondary:hover {
      background-color: #b89f8f;
    }

    .form-label {
      color: #5a3e36;
      font-weight: 600;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid #c89f94;
      box-shadow: inset 0 1px 3px rgba(0,0,0,.1);
    }

    textarea.form-control {
      resize: vertical;
      min-height: 100px;
    }

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

    form {
      background-color: #f8eadd;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }
  </style>
</head>
<body class="container py-4">

  <h2>Submit Excuse</h2>
  <button type="button" class="btn btn-secondary mb-3" onclick="window.history.back();">Back</button>

  <?php if(!empty($_SESSION['success'])){ echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); } ?>
  <?php if(!empty($_SESSION['error'])){ echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); } ?>

  <form method="POST" enctype="multipart/form-data" action="../core/handleForms.php">
    <input type="hidden" name="action" value="submitExcuse">

    <div class="mb-2">
      <label class="form-label">Reason:</label>
      <textarea name="reason" class="form-control" required></textarea>
    </div>

    <div class="mb-2">
      <label class="form-label">Upload File (optional):</label>
      <input type="file" name="excuse_file" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Submit Excuse</button>
  </form>

</body>
</html>
