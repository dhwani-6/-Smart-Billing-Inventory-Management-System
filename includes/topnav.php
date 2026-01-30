<?php
/**
 * Reusable Top Navigation Component
 * 
 * Displays page title and user info
 */

// Get unread notifications
$unread_count = 0;
if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = $_SESSION['user_id'];
    $notify_res = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = $uid AND is_read = 0");
    if ($notify_res) {
        $unread_count = $notify_res->fetch_assoc()['count'];
    }
}
?>

<!-- Top Header -->
<div class="top-header">
    <h5>
        <?php echo $page_title ?? 'Dashboard'; ?>
    </h5>
    <div class="user-info">
        <a href="../admin/notifications.php" class="text-secondary me-3 position-relative" title="Notifications">
            <i class="fas fa-bell fa-lg"></i>
            <?php if ($unread_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem;">
                    <?php echo $unread_count > 99 ? '99+' : $unread_count; ?>
                </span>
            <?php endif; ?>
        </a>
        <span>
            <i class="fas fa-user-circle me-2"></i>
            Welcome,
            <?php echo $_SESSION['name']; ?>
        </span>
        <span class="badge">
            <?php echo ucfirst($_SESSION['role']); ?>
        </span>
        <a href="../auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt me-1"></i>Logout
        </a>
    </div>
</div>