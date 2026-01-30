<?php
// setup_notifications.php
require_once 'config/db.php';

echo "Setting up Notification System...\n";

// 1. Add added_by column to items table
$check_col = $conn->query("SHOW COLUMNS FROM items LIKE 'added_by'");
if ($check_col->num_rows == 0) {
    $sql = "ALTER TABLE items ADD COLUMN added_by INT NULL AFTER quantity";
    if ($conn->query($sql) === TRUE) {
        echo "Successfully added 'added_by' column to items table.\n";

        // Update existing items to be owned by Admin (ID 1) by default
        $conn->query("UPDATE items SET added_by = 1 WHERE added_by IS NULL");
        echo "Updated existing items to default Admin owner (ID 1).\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
} else {
    echo "'added_by' column already exists.\n";
}

// 2. Create notifications table
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Successfully created/verified 'notifications' table.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// 3. Check existing low stock items and create alerts
echo "Checking for existing low stock items...\n";
$result = $conn->query("SELECT * FROM items WHERE quantity < 10");

$count = 0;
if ($result) {
    while ($item = $result->fetch_assoc()) {
        $msg = "Low Stock Alert: Item '{$item['item_name']}' (ID: {$item['item_number']}) is running low. Current Quantity: {$item['quantity']}";

        // Notify Admin (Assume ID 1 is always admin, or fetch all admins)
        // Let's fetch all admins
        $admins = $conn->query("SELECT id FROM users WHERE role = 'admin'");
        while ($admin = $admins->fetch_assoc()) {
            createNotificationIfNew($conn, $admin['id'], $msg);
        }

        // Notify Owner if different from admin found above (to avoid duplicate if owner is admin)
        // If owner is not an admin (already notified), notify them.
        // Actually, let's just send to Owner. And send to Admins.
        // Note: createNotificationIfNew checks for duplicate messages to avoid spamming every time this script runs.

        if ($item['added_by'] && $item['added_by'] != 1) { // Assuming 1 is the main admin
            createNotificationIfNew($conn, $item['added_by'], $msg);
        }

        $count++;
    }
}

echo "Processed $count low stock items.\n";
echo "Setup Complete!";

function createNotificationIfNew($conn, $user_id, $message)
{
    // Check if distinct message exists for this user in the last 24 hours
    // (To avoid repitition if script is run multiple times)
    $safe_msg = $conn->real_escape_string($message);
    $check = $conn->query("SELECT id FROM notifications 
                          WHERE user_id = $user_id 
                          AND message = '$safe_msg' 
                          AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");

    if ($check->num_rows == 0) {
        $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$safe_msg')";
        $conn->query($sql);
        echo "Notification created for User ID $user_id.\n";
    }
}
?>