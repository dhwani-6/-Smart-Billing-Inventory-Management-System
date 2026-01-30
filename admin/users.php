<?php
/**
 * Users Page - User Management (Admin Only)
 * Phase 1 Requirement
 */

session_start();
require_once '../config/db.php';
requireLogin();

// RESTRICT TO ADMIN
if (!isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$page_title = 'Users';

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY name ASC");

include '../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <div class="page-header">
            <h4><i class="fas fa-user-shield me-2"></i>User Management</h4>
            <a href="add_user.php" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Add New User
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?= $u['id']; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($u['name']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($u['email']); ?>
                                </td>
                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Staff</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= date('Y-m-d', strtotime($u['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>