<?php
/**
 * Notifications Page
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

$page_title = 'Notifications';
$user_id = $_SESSION['user_id'];

// Mark all as read if requested
if (isset($_POST['mark_all_read'])) {
    $conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
    $_SESSION['success'] = "All notifications marked as read.";
    header("Location: notifications.php");
    exit;
}

// Fetch notifications
$result = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC");

// Include header
include '../includes/header.php';
?>

<!-- Include Sidebar -->
<?php include '../includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-bell me-2"></i>Notifications</h4>
            <?php if ($result->num_rows > 0): ?>
                <form method="POST">
                    <button type="submit" name="mark_all_read" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-check-double me-1"></i> Mark All Read
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="list-group list-group-flush">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="list-group-item <?php echo $row['is_read'] ? 'bg-light text-muted' : ''; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <?php if (!$row['is_read']): ?>
                                        <span class="badge bg-danger me-2">New</span>
                                    <?php endif; ?>
                                    System Alert
                                </h6>
                                <small class="text-muted">
                                    <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?>
                                </small>
                            </div>
                            <p class="mb-1">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="list-group-item text-center py-5 text-muted">
                        <i class="fas fa-bell-slash fa-3x mb-3"></i>
                        <p>No notifications found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>