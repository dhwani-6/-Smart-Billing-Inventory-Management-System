<?php
/**
 * Database Migration Script - Add image column to items table
 * Run this file once via browser to update the database schema
 */

require_once '../config/db.php';

try {
    // Add image column to items table
    $sql = "ALTER TABLE items ADD COLUMN image VARCHAR(255) NULL AFTER quantity";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Migration successful! Image column has been added to the items table.<br>";
        echo "You can now upload images for your items.<br>";
        echo "<br><a href='items.php'>Go to Items Page</a>";
    } else {
        echo "Migration failed: " . $conn->error;
    }
} catch (Exception $e) {
    // Column might already exist
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "✓ Image column already exists. No migration needed.<br>";
        echo "<br><a href='items.php'>Go to Items Page</a>";
    } else {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
