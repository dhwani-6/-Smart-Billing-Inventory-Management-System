<?php
/**
 * Dashboard - Phase 3 & 7 (Polish)
 * 
 * Modular dashboard with session protection, accurate stats, and Charts.
 */

session_start();
require_once '../config/db.php';
requireLogin();

$page_title = 'Dashboard';
$flash = getFlash();

// --- 1. STATS LOGIC ---

// Staff Count
$staff_count = 0;
$staff_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'staff'");
if ($staff_result) {
    $staff_count = $staff_result->fetch_assoc()['count'];
}

// Product Count (FIXED: Table is 'items', not 'products')
$product_count = 0;
$prod_result = $conn->query("SELECT COUNT(*) as count FROM items");
if ($prod_result) {
    $product_count = $prod_result->fetch_assoc()['count'];
}

include '../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Banner -->
        <div class="alert alert-primary shadow-sm mb-4 border-0"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h4 class="mb-1"><i class="fas fa-smile-beam me-2"></i>Welcome!</h4>
            <p class="mb-0 fa-1x">Here's what's happening in your store today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3 me-3 text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?php echo $staff_count; ?></h3>
                            <p class="text-muted mb-0">Total Staff Members</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3 me-3 text-success">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?php echo $product_count; ?></h3>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>