<?php
/**
 * Database Configuration - Phase 1
 * 
 * Handles database connection and basic authentication utilities
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_billing');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

/**
 * Sanitize user input
 */
function sanitize($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /smart-billing/index.php');
        exit();
    }
}

/**
 * Set flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

/**
 * Get and clear flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash_message'])) {
        $flash = [
            'type' => $_SESSION['flash_type'],
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Create a notification for a user
 */
function createNotification($user_id, $message)
{
    global $conn;
    $safe_msg = $conn->real_escape_string($message);
    $check = $conn->query("SELECT id FROM notifications
                          WHERE user_id = $user_id
                          AND message = '$safe_msg'
                          AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");

    if ($check->num_rows == 0) {
        $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$safe_msg')";
        return $conn->query($sql);
    }
    return false;
}

/**
 * Check and create low stock notification
 */
function checkAndCreateLowStockNotification($item_id, $item_name, $quantity, $added_by)
{
    global $conn;

    if ($quantity < 10) {
        $msg = "Low Stock Alert: Item '$item_name' (ID: $item_id) is running low. Current Quantity: $quantity";

        // Notify Admins
        $admins = $conn->query("SELECT id FROM users WHERE role = 'admin'");
        if ($admins) {
            while ($admin = $admins->fetch_assoc()) {
                createNotification($admin['id'], $msg);
            }
        }

        // Notify Owner if exists and not an admin (or just notify them too)
        if ($added_by) {
            createNotification($added_by, $msg);
        }
        return true;
    }
    return false;
}