<?php
/**
 * Add Customer Page
 * Phase 2 Requirement
 */

session_start();
require_once '../config/db.php';
requireLogin();

// Form Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize
    $customer_name = sanitize($_POST['customer_name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);

    // Validation
    if (empty($customer_name) || empty($phone)) {
        $_SESSION['error'] = "Name and Phone are required!";
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, phone, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $customer_name, $phone, $email, $address);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Customer added successfully!";
            header('Location: customer.php');
            exit;
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
    }
}

$page_title = 'Add Customer';
include '../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <?php include '../includes/topnav.php'; ?>

    <div class="content-body">
        <div class="page-header">
            <h4><i class="fas fa-user-plus me-2"></i>Add New Customer</h4>
            <a href="customer.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" required
                                placeholder="Ex: John Doe">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required placeholder="Ex: 9876543210">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Optional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"
                            placeholder="Customer Address"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>