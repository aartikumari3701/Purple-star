<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = ""; // Default for XAMPP (no password)
$dbName = "login_register";
$port = 3308; // Updated MySQL port

// Create the database connection with the custom port
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $port);

// Check if the connection is successful
if (!$conn) {
    // Log the error to a file instead of displaying it directly
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection error. Please try again later.");
}

// Optional: Set the character set to utf8mb4 for proper handling of characters
mysqli_set_charset($conn, "utf8mb4");
?>
