<?php 
session_start();
include 'dbconnection.php';

// Check if cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Handle the action (remove an item from the cart)
if (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['id'])) {
    $itemId = $_POST['id'];

    // Remove the item with the matching ID
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$index]); // Remove the item
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array after removal
            echo json_encode(["status" => "success", "message" => "Item removed successfully"]);
            exit;
        }
    }

    // If item was not found in the cart
    echo json_encode(["status" => "error", "message" => "Item not found in cart"]);
    exit;
}

// Default response if no action is provided
echo json_encode(["status" => "error", "message" => "Invalid action or parameters"]);
exit;
?>
