<?php
/**
 * Items Page - Inventory List with Images
 */

session_start();
require_once '../config/db.php';

// Session Protection
requireLogin();

// Set page title
$page_title = 'Items';

// Fetch all items
$items_result = $conn->query("SELECT * FROM items ORDER BY item_number ASC");

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
            <h4><i class="fas fa-box me-2"></i>Items Management</h4>
            <a href="add_item.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Item
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <div class="table-controls">
                <div class="entries-control">
                    <span>Show</span>
                    <select>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-box">
                    <label>Search:</label>
                    <input type="text" id="searchInput" placeholder="">
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item Number</th>
                        <th>Item Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php
                                $img_path = "../uploads/items/" . $item['image'];
                                if ($item['image'] && file_exists($img_path)):
                                    ?>
                                    <div class="item-image-wrapper">
                                        <img src="<?= $img_path; ?>" class="item-thumbnail"
                                            alt="<?= htmlspecialchars($item['item_name']); ?>">
                                        <div class="image-popup">
                                            <img src="<?= $img_path; ?>" alt="<?= htmlspecialchars($item['item_name']); ?>">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item['item_number']; ?></td>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td>₹ <?php echo number_format($item['unit_price'], 2); ?></td>
                            <td>
                                <?php if ($item['quantity'] < 10): ?>
                                    <span class="badge badge-danger"><?= $item['quantity']; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $item['quantity']; ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <a href="edit_item.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_item.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <div>Showing 1 to
                    <?php echo $items_result->num_rows; ?> of
                    <?php echo $items_result->num_rows; ?> entries
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .item-image-wrapper {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .item-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .image-popup {
        display: none;
        position: absolute;
        top: 60px;
        left: 0;
        z-index: 1000;
        background: white;
        border: 2px solid #007bff;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .image-popup img {
        width: 250px;
        height: auto;
        border-radius: 4px;
    }

    .item-image-wrapper:hover .image-popup {
        display: block;
    }

    .no-image {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 4px;
        color: #6c757d;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #28a745;
        color: white;
    }

    .badge-danger {
        background: #dc3545;
        color: white;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
        margin-right: 5px;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
        border: none;
    }

    .btn-info:hover {
        background: #138496;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
    }

    .btn-danger:hover {
        background: #c82333;
    }
</style>

<script>
    // Simple search functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>

<!-- Include Footer -->
<?php include '../includes/footer.php'; ?>