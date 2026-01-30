<?php
/**
 * Reports Page - Consolidated
 * Single page with client-side tabs for instant switching (Premium Feel)
 */

session_start();
require_once '../config/db.php';
requireLogin();

$page_title = 'Reports';

// --- ACTIVE TAB LOGIC (Server-Side) ---
// Determined by GET param, default to 'item'
$active_tab = isset($_GET['active_tab']) ? $_GET['active_tab'] : 'item';

// --- DATA FETCHING ---

// 1. ITEMS
$items_sql = "SELECT id as product_id, item_number, item_name, discount, quantity as stock, unit_price, status, description FROM items ORDER BY item_number ASC";
$items_result = $conn->query($items_sql);

// 2. SALES
// Sanitize Inputs for SQL Safety
$start_date = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : date('Y-m-d');
$show_sale_report = isset($_GET['show_report']);

$sales_where = "";
if ($show_sale_report) {
    // Basic verification to protect against empty strings if sanitize fails
    if ($start_date && $end_date) {
        $sales_where = "WHERE s.sale_date BETWEEN '$start_date' AND '$end_date'";
    }
}

$sales_sql = "SELECT s.id as sale_id, si.item_number, s.customer_id, s.customer_name, si.item_name, s.sale_date, si.discount as discount_percent, si.quantity, si.unit_price, si.total_price 
              FROM sales s JOIN sale_items si ON s.id = si.sale_id 
              $sales_where ORDER BY s.id ASC";
$sales_result = $conn->query($sales_sql);


// 3. OTHER TABS (Placeholders/Basic)
$cust_sql = "SELECT id, customer_name, phone, email, address FROM customers";
$cust_result = $conn->query($cust_sql);

$purch_sql = "SELECT purchase_id, item_name, vendor_name, purchase_date, total_price FROM purchases";
$purch_result = $conn->query($purch_sql);

$vend_sql = "SELECT id, vendor_name, contact_person, phone FROM vendors";
$vend_result = $conn->query($vend_sql);

include '../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <div class="report-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 text-dark">Reports</h4>
            <button class="btn btn-refresh" onclick="location.href='reports.php'">Refresh</button>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 4px;">

            <!-- Custom Tab Navigation -->
            <ul class="nav nav-tabs tab-nav" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'item') ? 'active' : ''; ?>" id="item-tab"
                        data-bs-toggle="tab" data-bs-target="#item" type="button" role="tab">Item</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'customer') ? 'active' : ''; ?>"
                        id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button"
                        role="tab">Customer</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'sale') ? 'active' : ''; ?>" id="sale-tab"
                        data-bs-toggle="tab" data-bs-target="#sale" type="button" role="tab">Sale</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'purchase') ? 'active' : ''; ?>"
                        id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button"
                        role="tab">Purchase</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'vendor') ? 'active' : ''; ?>" id="vendor-tab"
                        data-bs-toggle="tab" data-bs-target="#vendor" type="button" role="tab">Vendor</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_tab == 'forecast') ? 'active' : ''; ?>"
                        id="forecast-tab" data-bs-toggle="tab" data-bs-target="#forecast" type="button"
                        role="tab">Inventory Forecast</button>
                </li>
            </ul>

            <div class="card-body p-4">
                <div class="tab-content" id="reportTabsContent">

                    <!-- ITEM TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'item') ? 'show active' : ''; ?>" id="item"
                        role="tabpanel">
                        <p class="text-muted mb-4">Use the grid below to get reports for items</p>
                        <div class="table-responsive">
                            <table id="itemTable" class="table table-hover data-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Product ID</th>
                                        <th>Item Number</th>
                                        <th>Item Name</th>
                                        <th>Discount %</th>
                                        <th>Stock</th>
                                        <th>Unit Price</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $items_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['product_id']; ?></td>
                                            <td><?php echo $row['item_number']; ?></td>
                                            <td><a href="#"
                                                    class="text-decoration-none"><?php echo htmlspecialchars($row['item_name']); ?></a>
                                            </td>
                                            <td><?php echo $row['discount'] + 0; ?></td>
                                            <td><?php echo $row['stock']; ?></td>
                                            <td><?php echo number_format($row['unit_price'], 0, '', ''); ?></td>
                                            <td><span class="text-success"><?php echo $row['status']; ?></span></td>
                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CUSTOMER TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'customer') ? 'show active' : ''; ?>"
                        id="customer" role="tabpanel">
                        <div class="table-responsive">
                            <table id="customerTable" class="table table-hover data-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th>ID</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $cust_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SALE TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'sale') ? 'show active' : ''; ?>" id="sale"
                        role="tabpanel">
                        <form method="GET" action="reports.php" class="mb-4">
                            <!-- Preserve active tab -->
                            <input type="hidden" name="active_tab" value="sale">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-bold">Start Date</label>
                                    <input type="date" name="start_date" value="<?php echo $start_date; ?>"
                                        class="form-control bg-light border-0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-bold">End Date</label>
                                    <input type="date" name="end_date" value="<?php echo $end_date; ?>"
                                        class="form-control bg-light border-0">
                                </div>
                            </div>
                            <div>
                                <button type="submit" name="show_report" class="btn btn-dark px-3 py-2 me-2"
                                    style="background-color: #343a40 !important;">Show Report</button>
                                <a href="reports.php?active_tab=sale" class="btn btn-light px-3 py-2 border">Clear</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="saleTable" class="table table-hover data-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Sale ID</th>
                                        <th>Item #</th>
                                        <th>Cust ID</th>
                                        <th>Customer Name</th>
                                        <th>Item Name</th>
                                        <th>Sale Date</th>
                                        <th>Disc %</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($sales_result && $sales_result->num_rows > 0): ?>
                                        <?php while ($row = $sales_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['sale_id']; ?></td>
                                                <td><?php echo $row['item_number']; ?></td>
                                                <td><?php echo $row['customer_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                                <td><?php echo $row['sale_date']; ?></td>
                                                <td><?php echo $row['discount_percent'] + 0; ?></td>
                                                <td><?php echo $row['quantity']; ?></td>
                                                <td><?php echo number_format($row['unit_price'], 0, '', ''); ?></td>
                                                <td><?php echo number_format($row['total_price'], 0, '', ''); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PURCHASE TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'purchase') ? 'show active' : ''; ?>"
                        id="purchase" role="tabpanel">
                        <div class="table-responsive">
                            <table id="purchaseTable" class="table table-hover data-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th>ID</th>
                                        <th>Item Name</th>
                                        <th>Vendor</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $purch_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['purchase_id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['vendor_name']); ?></td>
                                            <td><?php echo $row['purchase_date']; ?></td>
                                            <td><?php echo number_format($row['total_price'], 0, '', ''); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- VENDOR TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'vendor') ? 'show active' : ''; ?>" id="vendor"
                        role="tabpanel">
                        <div class="table-responsive">
                            <table id="vendorTable" class="table table-hover data-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th>ID</th>
                                        <th>Vendor Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $vend_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['vendor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- FORECAST TAB -->
                    <div class="tab-pane fade <?php echo ($active_tab == 'forecast') ? 'show active' : ''; ?>"
                        id="forecast" role="tabpanel">
                        <div class="alert alert-info">Inventory Forecasting module coming soon.</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>