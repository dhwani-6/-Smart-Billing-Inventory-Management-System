<?php
/**
 * Purchase Page - Purchase History
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Purchase';

// Fetch all purchases
$purchases_result = $conn->query("SELECT * FROM purchases ORDER BY purchase_date DESC");

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
            <h4><i class="fas fa-shopping-cart me-2"></i>Search Inventory</h4>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-nav">
            <a href="items.php">Item</a>
            <a href="customer.php">Customer</a>
            <a href="sale.php">Sale</a>
            <a href="purchase.php" class="active">Purchase</a>
            <a href="vendor.php">Vendor</a>
        </div>

        <div class="table-wrapper" style="border-radius: 0 0 8px 8px;">
            <div style="padding: 20px; background: #f8f9fc;">
                <p style="margin: 0; color: #5a5c69;">Use the grid below to search purchase details</p>
            </div>

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
                        <th>Purchase ID</th>
                        <th>Item Number</th>
                        <th>Purchase Date</th>
                        <th>Item Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Vendor Name</th>
                        <th>Vendor ID</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($purchase = $purchases_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $purchase['purchase_id']; ?>
                            </td>
                            <td>
                                <?php echo $purchase['item_number']; ?>
                            </td>
                            <td>
                                <?php echo date('Y-m-d', strtotime($purchase['purchase_date'])); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($purchase['item_name']); ?>
                            </td>
                            <td>
                                <?php echo number_format($purchase['unit_price'], 0); ?>
                            </td>
                            <td>
                                <?php echo $purchase['quantity']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($purchase['vendor_name']); ?>
                            </td>
                            <td>
                                <?php echo $purchase['vendor_id']; ?>
                            </td>
                            <td>
                                <?php echo number_format($purchase['total_price'], 0); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $purchases_result->num_rows; ?> of
                    <?php echo $purchases_result->num_rows; ?> entries
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