<?php
session_start();
include('csrf.php'); // CSRF protection
include('dbconnection.php'); // Centralized DB connection

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    try {
        // Validate CSRF token
        validateCsrfToken($_POST['csrf_token']);

        // Retrieve and sanitize inputs
        $productName = trim($_POST['product_name']);
        $productPrice = trim($_POST['product_price']);
        $productImage = $_FILES['product_image'];

        if (empty($productName) || !is_numeric($productPrice) || $productPrice <= 0) {
            throw new Exception("Invalid product name or price.");
        }

        // Handle image upload
        $imageName = uniqid() . "_" . basename($productImage['name']);
        $targetDir = 'uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $imageName;
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        if ($productImage['size'] > 5000000) {
            throw new Exception("File size exceeds 5MB.");
        }

        if (!move_uploaded_file($productImage['tmp_name'], $targetFile)) {
            throw new Exception("Error uploading image.");
        }

        // Insert the new product
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $productName, $productPrice, $imageName);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Product added successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: admin_dashboard.php");
    exit;
}

// Handle deleting a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
    try {
        // Validate CSRF token
        validateCsrfToken($_POST['csrf_token']);

        $productId = intval($_POST['product_id']);
        if ($productId <= 0) {
            throw new Exception("Invalid product ID.");
        }

        // Delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Product deleted successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your styles here */
    </style>
</head>
<body>

    <!-- Topbar -->
    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h1>Admin Dashboard</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="#">Dashboard</a>
        <a href="#">Manage Products</a>
        <a href="#">Orders</a>
        <a href="#">Settings</a>
        <a href="index.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Display Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="alert error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <h2>Add New Product</h2>
        <form action="admin_dashboard.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>
            <label for="product_price">Product Price:</label>
            <input type="number" step="0.01" name="product_price" id="product_price" required>
            <label for="product_image">Product Image:</label>
            <input type="file" name="product_image" id="product_image" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <h2>Product List</h2>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h6><?php echo $row['name']; ?></h6>
                    <p>$<?php echo $row['price']; ?></p>
                    <form action="admin_dashboard.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_product">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- JavaScript to Toggle Sidebar -->
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
