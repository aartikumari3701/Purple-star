<?php
session_start();
include 'dbconnection.php'; // Include your database connection
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
// Check if the order ID is provided in the URL
if (!isset($_GET['order_id'])) {
    header('Location: index.php'); // Redirect to homepage if no order ID is provided
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order details from the database
$order_query = "SELECT * FROM orders WHERE id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Fetch order items
$order_items_query = "SELECT * FROM order_items WHERE order_id = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);

// Calculate the total price (just in case you want to show it again)
$total_price = 0;
$order_items = [];
while ($item = mysqli_fetch_assoc($order_items_result)) {
    $order_items[] = $item;
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cart_styles.css">
</head>
<body>
<div class="super_container">
    <?php include 'include/navbar.php'; ?>

    <div class="container order_confirmation_container">
        <h2>Order Confirmation</h2>
        
        <div class="order_summary">
            <h4>Order ID: <?php echo $order['id']; ?></h4>
            <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
            <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
            <p><strong>City:</strong> <?php echo $order['city']; ?></p>
            <p><strong>State:</strong> <?php echo $order['state']; ?></p>
            <p><strong>ZIP Code:</strong> <?php echo $order['zip']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['email']; ?></p>

            <h4>Order Items:</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <?php
                                // Get product name based on the product ID (Assuming you have a 'products' table)
                                $product_id = $item['product_id'];
                                $product_query = "SELECT name FROM products WHERE id = '$product_id'";
                                $product_result = mysqli_query($conn, $product_query);
                                $product = mysqli_fetch_assoc($product_result);
                                echo $product['name'];
                                ?>
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart_total">
                <h4>Total: $<?php echo number_format($total_price, 2); ?></h4>
            </div>

            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
        </div>

        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>

    <?php include 'include/footer.php'; ?>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
</body>
</html>
