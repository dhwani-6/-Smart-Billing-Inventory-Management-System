<?php
/**
 * Vendor Page - Vendor List
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Vendor';

// Fetch all vendors
$vendors_result = $conn->query("SELECT * FROM vendors ORDER BY vendor_name ASC");

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
            <h4><i class="fas fa-truck me-2"></i>Vendor Management</h4>
        </div>

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
                        <th>Vendor Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($vendor = $vendors_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $vendor['id']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($vendor['vendor_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($vendor['contact_person']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($vendor['phone']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($vendor['email']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($vendor['address']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $vendors_result->num_rows; ?> of
                    <?php echo $vendors_result->num_rows; ?> entries
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