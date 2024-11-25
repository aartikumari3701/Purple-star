<?php
session_start();
include('dbconnection.php'); // Use centralized database connection

try {
    // Validate and sanitize input
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        throw new Exception("Invalid product ID.");
    }
    $product_id = intval($_POST['product_id']);

    // Prepare the DELETE query to avoid SQL injection
    $sql = "DELETE FROM products WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product deleted successfully.";
        } else {
            throw new Exception("Error deleting product: " . $stmt->error);
        }

        $stmt->close();
    } else {
        throw new Exception("Error preparing SQL: " . $conn->error);
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
}

// Close the database connection
$conn->close();

// Redirect to admin dashboard with feedback
header("Location: admin_dashboard.php");
exit;
?>
