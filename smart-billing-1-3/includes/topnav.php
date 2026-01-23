<?php
/**
 * Reusable Top Navigation Component
 * 
 * Displays page title and user info
 */
?>

<!-- Top Header -->
<div class="top-header">
    <h5>
        <?php echo $page_title ?? 'Dashboard'; ?>
    </h5>
    <div class="user-info">
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