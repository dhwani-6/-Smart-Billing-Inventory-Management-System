<?php
/**
 * Invoice Page - Invoice List with Line Items
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Invoice';

// Fetch all invoices with details
$invoices_result = $conn->query("
    SELECT s.*, 
           GROUP_CONCAT(si.item_name SEPARATOR ', ') as items
    FROM sales s
    LEFT JOIN sale_items si ON s.id = si.sale_id
    GROUP BY s.id
    ORDER BY s.sale_date DESC
");

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
            <h4><i class="fas fa-file-invoice me-2"></i>Invoice Management</h4>
            <a href="create_invoice.php" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> New Invoice
            </a>
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
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($invoice = $invoices_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($invoice['invoice_number']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($invoice['customer_name']); ?>
                            </td>
                            <td>
                                <?php echo date('Y-m-d', strtotime($invoice['sale_date'])); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($invoice['items']); ?>
                            </td>
                            <td>
                                <?php echo number_format($invoice['total_amount'], 2); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $invoices_result->num_rows; ?> of
                    <?php echo $invoices_result->num_rows; ?> entries
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