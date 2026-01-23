<?php
/**
 * Reusable Sidebar Component
 * 
 * Displays navigation menu
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h4>
            <i class="fas fa-receipt me-2"></i>
            Smart Billing
        </h4>
        <small>Management System</small>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="items.php" class="<?php echo ($current_page == 'items.php') ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                <span>Items</span>
            </a>
        </li>
        <li>
            <a href="customer.php" class="<?php echo ($current_page == 'customer.php') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li>
                <a href="users.php" class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>Users</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>