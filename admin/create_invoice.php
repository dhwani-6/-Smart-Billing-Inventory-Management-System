<?php
/**
 * Create Invoice - The Heart of the Billing System
 * 
 * Allows Admin/Staff to create new sales orders.
 * Features:
 * - Dynamic row addition
 * - Live stock validation
 * - Auto calculations
 */

session_start();
require_once '../config/db.php';
requireLogin();

$page_title = 'New Invoice';

// Fetch Customers for Dropdown
$customers = $conn->query("SELECT id, customer_name, phone FROM customers ORDER BY customer_name ASC");

// Fetch Products for Dropdown
$products = $conn->query("SELECT id, item_name, item_number, quantity, unit_price FROM items WHERE quantity > 0 ORDER BY item_name ASC");

// Generate Next Invoice Number
$last_inv = $conn->query("SELECT id FROM sales ORDER BY id DESC LIMIT 1");
$next_id = ($last_inv->num_rows > 0) ? $last_inv->fetch_assoc()['id'] + 1 : 1;
$invoice_number = 'INV-' . date('Y') . '-' . str_pad($next_id, 4, '0', STR_PAD_LEFT);

include '../includes/header.php';
?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }

    .bill-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .stock-badge {
        font-size: 0.8rem;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-cart-plus me-2"></i>New Invoice</h4>
            <span class="badge bg-primary fs-6">
                <?php echo $invoice_number; ?>
            </span>
        </div>

        <form action="save_invoice.php" method="POST" id="billingForm">
            <input type="hidden" name="invoice_number" value="<?php echo $invoice_number; ?>">

            <!-- Customer Section -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Customer</label>
                            <select name="customer_id" id="customerSelect" class="form-select select2" required>
                                <option value="">Select Customer...</option>
                                <?php while ($c = $customers->fetch_assoc()): ?>
                                    <option value="<?php echo $c['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($c['customer_name']); ?>">
                                        <?php echo htmlspecialchars($c['customer_name']); ?> (
                                        <?php echo $c['phone']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type="hidden" name="customer_name" id="customerNameField">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Invoice Date</label>
                            <input type="date" name="sale_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-2">
                            <!-- Placeholder for Add Customer Button if needed -->
                            <a href="customer.php" target="_blank" class="btn btn-outline-secondary btn-sm mt-2"><i
                                    class="fas fa-user-plus"></i> New Cust</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Grid -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="billingTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="40%">Item / Product</th>
                                    <th width="15%" class="text-end">Stock</th>
                                    <th width="15%" class="text-end">Price</th>
                                    <th width="12%">Qty</th>
                                    <th width="15%" class="text-end">Total</th>
                                    <th width="3%"></th>
                                </tr>
                            </thead>
                            <tbody id="billingRows">
                                <!-- Dynamic Rows Go Here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light border-top">
                        <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">
                            <i class="fas fa-plus me-1"></i> Add Product
                        </button>
                    </div>
                </div>
            </div>

            <!-- Totals & Actions -->
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="bill-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-bold" id="displaySubtotal">0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Discount (0%):</span> <!-- Placeholder for Discount logic -->
                            <span>- 0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0">Grand Total:</h5>
                            <h4 class="mb-0 text-primary fw-bold">₹ <span id="displayGrandTotal">0.00</span></h4>
                            <input type="hidden" name="total_amount" id="grandTotalInput">
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-check-circle me-2"></i> Save & Generate Invoice
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</main>

<!-- Hidden Template for JS to Clone -->
<template id="rowTemplate">
    <tr class="item-row">
        <td>
            <select name="items[]" class="form-select item-select" required>
                <option value="">Select Product...</option>
                <?php
                // Reset pointer to reuse
                $products->data_seek(0);
                while ($p = $products->fetch_assoc()):
                    ?>
                    <option value="<?php echo $p['id']; ?>" data-price="<?php echo $p['unit_price']; ?>"
                        data-stock="<?php echo $p['quantity']; ?>"
                        data-name="<?php echo htmlspecialchars($p['item_name']); ?>">
                        <?php echo htmlspecialchars($p['item_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="item_names[]" class="item-name-input">
        </td>
        <td class="text-end">
            <span class="stock-display text-muted">-</span>
        </td>
        <td class="text-end">
            <input type="hidden" name="prices[]" class="price-input">
            <span class="price-display">0.00</span>
        </td>
        <td>
            <input type="number" name="quantities[]" class="form-control qty-input" min="1" value="1" required>
        </td>
        <td class="text-end">
            <span class="row-total fw-bold">0.00</span>
        </td>
        <td class="text-center text-danger">
            <i class="fas fa-times remove-row cursor-pointer" style="cursor:pointer"></i>
        </td>
    </tr>
</template>

<?php include '../includes/footer.php'; ?>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {

        // Initialize Select2 for Customer
        $('#customerSelect').select2({
            placeholder: "Search Customer...",
            allowClear: true
        });

        // Update hidden name field on change
        $('#customerSelect').on('change', function () {
            var name = $(this).find(':selected').data('name');
            $('#customerNameField').val(name);
        });

        // --- ROW MANAGEMENT ---

        function addRow() {
            var template = document.getElementById('rowTemplate');
            var clone = template.content.cloneNode(true);
            $('#billingRows').append(clone);

            // Re-init Select2 on the new row? 
            // For performance, standard select is faster for lines, but Select2 is "Perfect".
            // Let's stick to standard select for rows to avoid clutter, or upgrade if user asks.
        }

        // Add first row by default
        addRow();

        $('#addItemBtn').click(function () {
            addRow();
        });

        // Remove row
        $(document).on('click', '.remove-row', function () {
            if ($('#billingRows tr').length > 1) {
                $(this).closest('tr').remove();
                calculateTotals();
            } else {
                alert("At least one item is required.");
            }
        });

        // --- CALCULATION LOGIC ---

        $(document).on('change', '.item-select', function () {
            var row = $(this).closest('tr');
            var option = $(this).find(':selected');
            var price = parseFloat(option.data('price')) || 0;
            var stock = parseInt(option.data('stock')) || 0;
            var name = option.data('name');

            // Update visuals
            row.find('.price-display').text(price.toFixed(2));
            row.find('.price-input').val(price);
            row.find('.item-name-input').val(name);
            row.find('.stock-display').text(stock);

            // Stock Validation Visuals
            var stockSpan = row.find('.stock-display');
            if (stock < 5) {
                stockSpan.removeClass('text-muted text-success').addClass('text-danger fw-bold');
            } else {
                stockSpan.removeClass('text-danger fw-bold').addClass('text-success');
            }

            // Set max attribute for quantity (Validation)
            row.find('.qty-input').attr('max', stock);

            calculateRow(row);
        });

        $(document).on('input', '.qty-input', function () {
            var row = $(this).closest('tr');
            var qty = parseInt($(this).val());
            var max = parseInt($(this).attr('max'));

            if (qty > max) {
                alert("Quantity exceeds available stock (" + max + ")");
                $(this).val(max);
            }

            calculateRow(row);
        });

        function calculateRow(row) {
            var price = parseFloat(row.find('.price-input').val()) || 0;
            var qty = parseInt(row.find('.qty-input').val()) || 0;
            var total = price * qty;

            row.find('.row-total').text(total.toFixed(2));
            calculateTotals();
        }

        function calculateTotals() {
            var grandTotal = 0;
            $('.item-row').each(function () {
                var rowTotal = parseFloat($(this).find('.row-total').text()) || 0;
                grandTotal += rowTotal;
            });

            $('#displaySubtotal').text(grandTotal.toFixed(2));
            $('#displayGrandTotal').text(grandTotal.toFixed(2));
            $('#grandTotalInput').val(grandTotal);
        }
    });
</script>