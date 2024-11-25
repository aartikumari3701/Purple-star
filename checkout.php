<?php
session_start();
include 'dbconnection.php'; // Include your database connection

// Redirect to the cart page if the cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if the form is submitted
if (isset($_POST['place_order'])) {
    // Fetch and validate selected shipping address
    $address_id = $_POST['address_id'] ?? null;

    if (!$address_id) {
        $error_message = "Please select a valid shipping address.";
    } else {
        // Check if the address belongs to the user
        $query = "SELECT * FROM addresses WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$address_id, $user_id]);
        $address = $stmt->fetch();

        if (!$address) {
            $error_message = "Invalid address selected.";
        } else {
            // Check if the user's email is verified
            $query = "SELECT email_verified FROM users WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if ($user['email_verified'] != 1) {
                $error_message = "Please verify your email before placing an order.";
            } else {
                // Calculate the total price
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total_price += $item['price'] * $item['quantity'];
                }

                // Insert the order into the database
                $query = "INSERT INTO orders (user_id, address_id, total_price, status) 
                          VALUES (?, ?, ?, 'Pending')";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$user_id, $address_id, $total_price]);

                // Retrieve the order ID
                $order_id = $pdo->lastInsertId();

                // Insert order items into the database
                foreach ($_SESSION['cart'] as $item) {
                    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
                }

                // Clear the cart
                unset($_SESSION['cart']);

                // Redirect to order confirmation
                header('Location: order_confirmation.php?order_id=' . $order_id);
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cart_styles.css">
</head>
<body>
<div class="super_container">
    <?php include 'include/navbar.php'; ?>

    <div class="container checkout_container">
        <h2>Checkout</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <h4>Select Shipping Address</h4>
                    <div class="form-group">
                        <label for="address_id">Choose Address</label>
                        <select name="address_id" id="address_id" class="form-control" required>
                            <option value="">Select an address</option>
                            <?php
                            // Fetch user's addresses
                            $query = "SELECT * FROM addresses WHERE user_id = ?";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([$user_id]);
                            $addresses = $stmt->fetchAll();

                            foreach ($addresses as $address):
                            ?>
                                <option value="<?php echo $address['id']; ?>">
                                    <?php echo $address['address_line1'] . ', ' . $address['city'] . ', ' . $address['country']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4>Your Cart</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_price = 0;
                            foreach ($_SESSION['cart'] as $item):
                                $subtotal = $item['price'] * $item['quantity'];
                                $total_price += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo $item['name']; ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart_total">
                        <h4>Total: $<?php echo number_format($total_price, 2); ?></h4>
                    </div>
                </div>
            </div>

            <button type="submit" name="place_order" class="btn btn-success btn-lg btn-block">Place Order</button>
        </form>
    </div>

    <?php include 'include/footer.php'; ?>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
</body>
</html>
