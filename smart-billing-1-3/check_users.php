<?php
require_once 'config/db.php';
$result = $conn->query("SELECT id, name, email, role FROM users");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Email: " . $row['email'] . " | Role: " . $row['role'] . "\n";
}
?>