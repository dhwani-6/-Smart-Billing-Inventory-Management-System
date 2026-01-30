<?php
/**
 * Customer Page - Customer List
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Customer';

// Fetch all customers
$customers_result = $conn->query("SELECT * FROM customers ORDER BY customer_name ASC");

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
        <div class="page-header">
            <h4><i class="fas fa-users me-2"></i>Customer Management</h4>
            <a href="add_customer.php" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Add New Customer
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <div class="table-controls">
                <div class="entries-control">
                    <span>Show</span>
                    <select>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-box">
                    <label>Search:</label>
                    <input type="text" id="searchInput" placeholder="">
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($customer = $customers_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $customer['id']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($customer['customer_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($customer['phone']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($customer['email']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($customer['address']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $customers_result->num_rows; ?> of
                    <?php echo $customers_result->num_rows; ?> entries
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Simple search functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>

<!-- Include Footer -->
<?php include '../includes/footer.php'; ?>