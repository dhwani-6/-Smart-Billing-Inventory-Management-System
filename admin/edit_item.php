<?php
/**
 * Edit Item Page
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Only admin can edit items
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Set page title
$page_title = 'Edit Item';

// Get item ID
$item_id = (int) $_GET['id'];

// Fetch item data
$result = $conn->query("SELECT * FROM items WHERE id = $item_id");
if ($result->num_rows === 0) {
    header('Location: items.php');
    exit;
}
$item = $result->fetch_assoc();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_number = (int) $_POST['item_number'];
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $unit_price = (float) $_POST['unit_price'];
    $quantity = (int) $_POST['quantity'];
    $image = $item['image']; // Keep old image by default

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Delete old image
            if ($item['image'] && file_exists("../uploads/items/" . $item['image'])) {
                unlink("../uploads/items/" . $item['image']);
            }

            $image = time() . "_" . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/items/" . $image);
        }
    }

    $sql = "UPDATE items SET 
            item_number = $item_number, 
            item_name = '$item_name', 
            unit_price = $unit_price, 
            quantity = $quantity, 
            image = '$image' 
            WHERE id = $item_id";

    if ($conn->query($sql) === TRUE) {
        checkAndCreateLowStockNotification($item_id, $item_name, $quantity, $item['added_by']);

        $_SESSION['success'] = "Item updated successfully!";
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
            <h4><i class="fas fa-edit me-2"></i>Edit Item</h4>
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
                        <input type="number" name="item_number" class="form-control"
                            value="<?= $item['item_number']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control"
                            value="<?= htmlspecialchars($item['item_name']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Price (₹)</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control"
                            value="<?= $item['unit_price']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="<?= $item['quantity']; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Image</label>
                    <?php if ($item['image']): ?>
                        <div class="mb-2">
                            <img src="../uploads/items/<?= $item['image']; ?>" width="100" class="rounded border">
                            <small class="d-block text-muted">Current Image</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Include Footer -->
<?php include '../includes/footer.php'; ?>