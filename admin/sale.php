<?php
/**
 * Sale Page - Sales/Invoice List
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Sale';

// Fetch all sales
$sales_result = $conn->query("SELECT * FROM sales ORDER BY sale_date DESC");

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
            <h4><i class="fas fa-dollar-sign me-2"></i>Sales Management</h4>
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
                        <th>Invoice Number</th>
                        <th>Customer Name</th>
                        <th>Sale Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($sale = $sales_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($sale['invoice_number']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($sale['customer_name']); ?>
                            </td>
                            <td>
                                <?php echo date('Y-m-d', strtotime($sale['sale_date'])); ?>
                            </td>
                            <td>
                                <?php echo number_format($sale['total_amount'], 2); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $sales_result->num_rows; ?> of
                    <?php echo $sales_result->num_rows; ?> entries
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