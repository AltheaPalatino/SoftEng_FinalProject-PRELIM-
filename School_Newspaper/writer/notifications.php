<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/classloader.php';
require_once __DIR__ . '/classes/Database.php'; 

if (!isset($userObj) || !$userObj->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$db = new Database();
$pdo = $db->getConnection();

if (isset($_GET['notif_id'])) {
    $notif_id = intval($_GET['notif_id']);
    if ($notif_id > 0) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $notif_id, 'user_id' => $user_id]);
    }
}

$stmt = $pdo->prepare("SELECT notification_id, message, is_read, created_at FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$allNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Notifications</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/notifications.css"> 
</head>
<body>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container mt-4">
  <h3>My Notifications</h3>
  <ul class="list-group mt-3">
    <?php if (!empty($allNotifications)): ?>
      <?php foreach ($allNotifications as $n): ?>
        <li class="list-group-item <?php echo $n['is_read'] ? '' : 'font-weight-bold'; ?>">
          <?php echo htmlspecialchars($n['message']); ?>
          <br><small class="text-muted"><?php echo $n['created_at']; ?></small>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li class="list-group-item">No notifications yet.</li>
    <?php endif; ?>
  </ul>
</div>
</body>
</html>
