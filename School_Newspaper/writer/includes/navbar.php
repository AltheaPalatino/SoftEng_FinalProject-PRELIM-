<?php
// writer/includes/navbar.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../classloader.php';

if (!isset($userObj) || !$userObj->isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

// base path for AJAX and links (works when current script is /School_Newspaper/writer/xxx.php)
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // e.g. /School_Newspaper/writer
$ajaxHandle = $basePath . '/core/handleForms.php';
$notificationsPage = $basePath . '/notifications.php';

// DB
$db = new Database();
$pdo = $db->getConnection();

$user_id = $_SESSION['user_id'] ?? null;
$notifications = [];
$unreadCount = 0;

if ($user_id) {
    // Fetch latest 5 notifications
    $stmt = $pdo->prepare("SELECT notification_id, message, is_read, created_at FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5");
    $stmt->execute(['user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Correctly fetch unread count
    $stmt2 = $pdo->prepare("SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
    $stmt2->execute(['user_id' => $user_id]);
    $unreadCount = (int)$stmt2->fetchColumn();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark p-3" style="background-color: #355E3B;">
  <a class="navbar-brand" href="index.php">Writer Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="articles_submitted.php">Articles Submitted</a></li>
      <li class="nav-item"><a class="nav-link" href="shared_articles.php">Shared Articles</a></li>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a id="notifDropdown" class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Notifications <span class="badge badge-danger" id="notifBadge"><?php echo $unreadCount; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifDropdown" style="min-width:320px;">
          <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
              <a class="dropdown-item notif-link <?php echo $notif['is_read'] ? '' : 'font-weight-bold'; ?>" 
                 data-id="<?php echo (int)$notif['notification_id']; ?>" href="<?php echo $notificationsPage; ?>">
                <?php echo htmlspecialchars($notif['message']); ?>
                <br><small class="text-muted"><?php echo $notif['created_at']; ?></small>
              </a>
            <?php endforeach; ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center" href="<?php echo $notificationsPage; ?>">View All</a>
          <?php else: ?>
            <a class="dropdown-item">No notifications</a>
          <?php endif; ?>
        </div>
      </li>
      <li class="nav-item"><a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a></li>
    </ul>
  </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
(function(){
  var ajaxHandle = "<?php echo $ajaxHandle; ?>";
  var notificationsPage = "<?php echo $notificationsPage; ?>";

  $(document).on("click", ".notif-link", function(e) {
      e.preventDefault();
      var notifId = $(this).data("id");

      $.post(ajaxHandle, { action: "markAsRead", notif_id: notifId }, function(response) {
          var success = false;
          try {
              var res = (typeof response === 'object') ? response : JSON.parse(response);
              success = !!res.success;
          } catch (err) {}
          finally {
              if (success) {
                  var $b = $("#notifBadge");
                  var cnt = parseInt($b.text()) || 0;
                  if (cnt > 0) $b.text(cnt - 1);
              }
              window.location.href = notificationsPage + "?notif_id=" + notifId;
          }
      }).fail(function() {
          window.location.href = notificationsPage + "?notif_id=" + notifId;
      });
  });
})();
</script>
