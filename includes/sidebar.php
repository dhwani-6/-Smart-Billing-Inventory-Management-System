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
            <a href="purchase.php" class="<?php echo ($current_page == 'purchase.php') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchase</span>
            </a>
        </li>
        <li>
            <a href="vendor.php" class="<?php echo ($current_page == 'vendor.php') ? 'active' : ''; ?>">
                <i class="fas fa-truck"></i>
                <span>Vendor</span>
            </a>
        </li>
        <li>
            <a href="sale.php" class="<?php echo ($current_page == 'sale.php') ? 'active' : ''; ?>">
                <i class="fas fa-dollar-sign"></i>
                <span>Sale</span>
            </a>
        </li>
        <li>
            <a href="customer.php" class="<?php echo ($current_page == 'customer.php') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Customer</span>
            </a>
        </li>
        <li>
            <a href="invoice.php" class="<?php echo ($current_page == 'invoice.php') ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice"></i>
                <span>Invoice</span>
            </a>
        </li>
        <li>
            <a href="reports.php" class="<?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li>
                <a href="users.php" class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>Users</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>