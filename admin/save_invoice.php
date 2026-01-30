<?php
/**
 * Save Invoice Handler
 * 
 * Processes the billing form.
 * USES TRANSACTIONS for Reliability (Phase 5 Requirement)
 */

session_start();
require_once '../config/db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Data Collection & Sanitization
    $invoice_number = $_POST['invoice_number'];
    $customer_id = (int) $_POST['customer_id'];
    $customer_name = $_POST['customer_name']; // Hidden field from frontend
    $sale_date = $_POST['sale_date'];
    $total_amount = (float) $_POST['total_amount'];

    // Arrays
    $item_ids = $_POST['items'];
    $item_names = $_POST['item_names'];
    $quantities = $_POST['quantities'];
    $prices = $_POST['prices'];

    if (empty($item_ids) || count($item_ids) === 0) {
        die("Error: No items in invoice.");
    }

    // 2. Start Transaction (CRITICAL for "Perfect" Project)
    $conn->begin_transaction();

    try {
        // A. Insert into Sales Table
        $stmt_sale = $conn->prepare("INSERT INTO sales (invoice_number, customer_id, customer_name, sale_date, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt_sale->bind_param("sissd", $invoice_number, $customer_id, $customer_name, $sale_date, $total_amount);
        $stmt_sale->execute();
        $sale_id = $conn->insert_id;
        $stmt_sale->close();

        // B. Loop Items: Insert Sale Items AND Update Stock
        $stmt_item = $conn->prepare("INSERT INTO sale_items (sale_id, item_number, item_name, unit_price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");

        // Prepare Stock Update Statement
        $stmt_stock = $conn->prepare("UPDATE items SET quantity = quantity - ? WHERE id = ?");

        for ($i = 0; $i < count($item_ids); $i++) {
            $item_id = $item_ids[$i]; // This is Product ID (PK)

            // We need item_number (SKU) for sale_items table to match schema. 
            // Let's quickly fetch it or just use ID as fallback if schema allows.
            // Schema has `item_number`. We should have fetched it in frontend but we didn't pass it.
            // Optimization: Let's fetch it now or trust item_id mapping.
            // Let's do a quick lookup to be safe/perfect.

            $query = $conn->query("SELECT item_number FROM items WHERE id = $item_id");
            $sku = ($query->num_rows > 0) ? $query->fetch_assoc()['item_number'] : $item_id;

            $name = $item_names[$i];
            $price = (float) $prices[$i];
            $qty = (int) $quantities[$i];
            $row_total = $price * $qty;

            // Insert Item
            $stmt_item->bind_param("iisdid", $sale_id, $sku, $name, $price, $qty, $row_total);
            $stmt_item->execute();

            // Update Stock (Decrement)
            $stmt_stock->bind_param("ii", $qty, $item_id);
            $stmt_stock->execute();
        }

        $stmt_item->close();
        $stmt_stock->close();

        // C. Commit Transaction
        $conn->commit();

        // Success!
        $_SESSION['success'] = "Invoice $invoice_number created successfully!";
        header('Location: invoice.php'); // Redirect to list
        exit;

    } catch (Exception $e) {
        // Rollback on any error
        $conn->rollback();
        die("Transaction Failed: " . $e->getMessage());
    }

} else {
    // Not POST
    header('Location: create_invoice.php');
}
?>