<?php
/**
 * Add Item Page
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Only admin can add items - restriction removed for staff
// if ($_SESSION['role'] !== 'admin') {
//     header('Location: dashboard.php');
//     exit;
// }

// Set page title
$page_title = 'Add Item';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_number = (int) $_POST['item_number'];
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $unit_price = (float) $_POST['unit_price'];
    $quantity = (int) $_POST['quantity'];
    $image = "";

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $image = time() . "_" . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/items/" . $image);
        }
    }

    $added_by = $_SESSION['user_id'];
    $sql = "INSERT INTO items (item_number, item_name, unit_price, quantity, image, added_by) 
            VALUES ($item_number, '$item_name', $unit_price, $quantity, '$image', $added_by)";

    if ($conn->query($sql) === TRUE) {
        $new_item_id = $conn->insert_id;
        checkAndCreateLowStockNotification($new_item_id, $item_name, $quantity, $added_by);

        $_SESSION['success'] = "Item added successfully!";
        header('Location: items.php');
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}

// Include header
include '../includes/header.php';
?>

<!-- Include Sidebar -->
<?php include '../includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <!-- Include Top Navigation -->
    <?php include '../includes/topnav.php'; ?>

    <!-- Content Body -->
    <div class="content-body">
        <div class="page-header">
            <h4><i class="fas fa-plus-circle me-2"></i>Add New Item</h4>
            <a href="items.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Items
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Number</label>
                        <input type="number" name="item_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Price (₹)</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Supported formats: JPG, PNG, GIF</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Include Footer -->
<?php include '../includes/footer.php'; ?>