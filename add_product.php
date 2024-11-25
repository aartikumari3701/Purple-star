<?php 
session_start();
include 'dbconnection.php';

// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();}

// Check if the action is provided and handle it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Handle "remove" action
    if ($action === 'remove' && isset($_POST['id'])) {
        $itemId = intval($_POST['id']); // Sanitize the ID input

        // Search for the item and remove it if found
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] === $itemId) {
                unset($_SESSION['cart'][$index]); // Remove the item
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
                echo json_encode(["status" => "success", "message" => "Item removed successfully"]);
                exit;
            }
        }

        // If item is not found in the cart
        echo json_encode(["status" => "error", "message" => "Item not found in cart"]);
        exit;
    }

    // Handle unsupported actions
    echo json_encode(["status" => "error", "message" => "Unsupported action"]);
    exit;
}

// Default response for invalid requests
echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
