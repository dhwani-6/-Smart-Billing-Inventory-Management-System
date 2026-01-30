<?php
/**
 * AJAX Handler - Get Product Details
 * 
 * Returns JSON data for item selection in Billing
 */

session_start();
require_once '../config/db.php';

// Security: Only logged in users
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Fetch item details
    // We select id, item_name, unit_price, quantity
    $stmt = $conn->prepare("SELECT id, item_name, unit_price, quantity FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();

        // Return JSON
        echo json_encode([
            'success' => true,
            'id' => $item['id'],
            'name' => $item['item_name'],
            'price' => $item['unit_price'],
            'stock' => $item['quantity']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }
    $stmt->close();
}
?>