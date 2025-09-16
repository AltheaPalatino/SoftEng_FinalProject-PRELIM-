<?php
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

// create a Database object to get access to PDO
$db = new Database();
$pdo = $db->getConnection();

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$notifications = $stmt->fetchAll();

$sqlUnread = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = :user_id AND is_read = 0";
$stmtUnread = $pdo->prepare($sqlUnread);
$stmtUnread->execute(['user_id' => $user_id]);
$unreadCount = $stmtUnread->fetch()['unread_count'];

$notificationsPage = "notifications.php"; 
?>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #008080;">
  <a class="navbar-brand" href="index.php">Admin Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="articles_from_students.php">Pending Articles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="articles_submitted.php">Articles Submitted</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php">Add Category</a>
      </li>
    </ul>

    <!-- Notifications Dropdown -->
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" data-toggle="dropdown">
          Notifications <span class="badge badge-danger" id="notifBadge"><?php echo $unreadCount; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notif): ?>
              <a class="dropdown-item <?php echo $notif['is_read'] ? '' : 'font-weight-bold'; ?> text-wrap notif-item" 
                 href="<?php echo $notificationsPage; ?>?id=<?php echo $notif['notification_id']; ?>" 
                 data-id="<?php echo $notif['notification_id']; ?>">
                <?php echo htmlspecialchars($notif['message']); ?>
                <br><small><?php echo $notif['created_at']; ?></small>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <a class="dropdown-item">No notifications</a>
          <?php endif; ?>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Scripts for dropdown & AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('.notif-item').on('click', function(e) {
        var notifId = $(this).data('id');

        $.post('core/handleForms.php', { action: 'markAsRead', notif_id: notifId }, function(data) {
            if(data.success) {
                var badge = $('#notifBadge');
                var count = parseInt(badge.text());
                if(count > 1){
                    badge.text(count - 1);
                } else {
                    badge.text('');
                }
            }
        }, 'json');
    });
});
</script>
