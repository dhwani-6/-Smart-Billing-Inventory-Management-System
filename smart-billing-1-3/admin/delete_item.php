<?php
/**
 * Delete Item
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Only admin can delete items
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Get item ID
$item_id = (int) $_GET['id'];

// Fetch item to get image filename
$result = $conn->query("SELECT image FROM items WHERE id = $item_id");
if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();

    // Delete image file
    if ($item['image'] && file_exists("../uploads/items/" . $item['image'])) {
        unlink("../uploads/items/" . $item['image']);
    }

    // Delete from database
    $conn->query("DELETE FROM items WHERE id = $item_id");
    $_SESSION['success'] = "Item deleted successfully!";
} else {
    $_SESSION['error'] = "Item not found!";
}

header('Location: items.php');
exit;
?>
