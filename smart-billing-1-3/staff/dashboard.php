<?php
/**
 * Dashboard - Phase 3
 * 
 * Modular dashboard with session protection and reusable includes
 */

session_start();
require_once '../config/db.php';

// PHASE 3: Session Protection - Prevent access without login
requireLogin();

// Set page title
$page_title = 'Dashboard';

// Get flash message if any
$flash = getFlash();

// Fetch counts from database
$staff_count = 0;
$product_count = 0;

// Get total staff count (from users table)
$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'staff'");
if ($result) {
    $staff_count = $result->fetch_assoc()['count'];
}

// Get total products count
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result) {
    $product_count = $result->fetch_assoc()['count'];
}

// Include header
include '../includes/header.php';
?>

<!-- Include Sidebar -->
<?php include '../includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <!-- Include Top Navigation -->
    <?php include '../includes/topnav.php'; ?>

    <!-- Content Body -->
    <div class="content-body">
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="stat-card staff">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?php echo $staff_count; ?></h3>
                    <p>Total Staff Members</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="stat-card products">
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3><?php echo $product_count; ?></h3>
                    <p>Total Products</p>
                </div>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <div class="chart-placeholder">
            <i class="fas fa-chart-bar"></i>
            <h5>Chart Placeholder</h5>
            <p>Analytics and graphs will be displayed here</p>
        </div>
    </div>
</main>

<!-- Include Footer -->
<?php include '../includes/footer.php'; ?>