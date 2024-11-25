<?php 
session_start();
include 'dbconnection.php'; // Include your database connection

// If cart session doesn't exist, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cart_styles.css">
</head>
<body>
<div class="super_container">
    <?php include 'include/navbar.php'; ?>

    <div class="container mt-5">
        <!-- Cart Section -->
        <h2>Your Cart</h2>
        <div class="row">
            <div class="col-md-8">
                <div id="cart-items-container">
                    <!-- Dynamically generated cart items will appear here -->
                </div>
            </div>
            <div class="col-md-4">
                <div class="order-summary">
                    <h4>Order Summary</h4>
                    <p class="total-price"><strong>Total: $0.00</strong></p>
                    <button id="checkoutButton" class="btn btn-success mt-3">Proceed to Checkout</button>
                </div>
            </div>
        </div>

        <!-- Booking Section -->
        <div id="booking1" class="mt-5" style="display:none;">
            <h2>Booking Details</h2>
            <div class="row">
                <div class="col-md-8">
                    <form id="bookingForm">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" rows="3" placeholder="Enter your address" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="order-summary">
                        <h4>Order Summary</h4>
                        <p class="total-price"><strong>Total: $0.00</strong></p>
                        <button id="proceedToPayButton" class="btn btn-primary mt-3">Proceed to Pay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'include/footer.php'; ?>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
// Cart management with localStorage
const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
const cartContainer = document.getElementById("cart-items-container");
const totalPriceElement = document.querySelector(".total-price");

// Render cart items
function renderCart() {
    cartContainer.innerHTML = '';
    let totalPrice = 0;

    cartItems.forEach((item, index) => {
        totalPrice += item.price * item.quantity;

        const cartItem = document.createElement("div");
        cartItem.className = "cart-item";
        cartItem.innerHTML = `
            <div class="d-flex">
                <img src="images/${item.image}" alt="${item.name}" class="cart-item-image">
                <div>
                    <p><strong>${item.name}</strong></p>
                    <p>Price: $${item.price.toFixed(2)}</p>
                    <p>Quantity: ${item.quantity}</p>
                </div>
            </div>
            <button class="btn btn-danger" onclick="removeItem(${index})">Remove</button>
        `;
        cartContainer.appendChild(cartItem);
    });

    totalPriceElement.textContent = `Total: $${totalPrice.toFixed(2)}`;
}

// Remove item from cart
function removeItem(index) {
    cartItems.splice(index, 1);
    localStorage.setItem("cartItems", JSON.stringify(cartItems));
    renderCart();
}

// Show booking section
document.getElementById("checkoutButton").onclick = () => {
    document.querySelector(".container.mt-5").style.display = "none";
    document.getElementById("booking1").style.display = "block";
};

// Razorpay integration
document.getElementById("proceedToPayButton").onclick = () => {
    const razorpayOptions = {
        key: "YOUR_RAZORPAY_KEY",
        amount: 10000, // Replace with total price in smallest currency unit
        currency: "INR",
        name: "Your Company",
        description: "Test Transaction",
        handler: function (response) {
            alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
        },
    };
    const razorpay = new Razorpay(razorpayOptions);
    razorpay.open();
};

// Initial render
renderCart();
</script>
</body>
</html>
