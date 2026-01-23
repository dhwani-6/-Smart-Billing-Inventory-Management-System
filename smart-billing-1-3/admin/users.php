<?php
/**
 * Users Page - Manage Users
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Only admin can access
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Set page title
$page_title = 'Users';

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

// Include header
include '../includes/header.php';
?>

<!-- Include Sidebar -->
<?php include '../includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <div class="page-header">
            <h4><i class="fas fa-users-cog me-2"></i>User Management</h4>
            <a href="../auth/register.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#
                                <?= $row['id']; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['name']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['email']); ?>
                            </td>
                            <td>
                                <?php if ($row['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Staff</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= date('M d, Y', strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>