<?php
// Start the session to access the cart
session_start();

// Set the Content-Type to JSON for the response
header('Content-Type: application/json');

// Check if the cart exists in the session, otherwise return an empty array
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Return the cart items as a JSON response
echo json_encode($cartItems);
?>
