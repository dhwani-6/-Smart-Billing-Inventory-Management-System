<?php
require_once 'config/db.php';

$hash = password_hash('password123', PASSWORD_DEFAULT);

// 1. Reset Admin
$conn->query("UPDATE users SET name='System Admin', role='admin', password='$hash' WHERE email='admin@billing.com'");

if ($conn->affected_rows > 0) {
    echo "Admin user reset successfully.\n";
} else {
    echo "Admin user found but no changes needed (or email not found).\n";
    // If email not found, recreate
    $check = $conn->query("SELECT id FROM users WHERE email='admin@billing.com'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('System Admin', 'admin@billing.com', '$hash', 'admin')");
        echo "Admin user recreated.\n";
    }
}

echo "\nCurrent Users:\n";
$result = $conn->query("SELECT name, email, role FROM users");
while ($row = $result->fetch_assoc()) {
    echo "Name: " . $row['name'] . " (" . $row['role'] . ") - " . $row['email'] . "\n";
}
?>