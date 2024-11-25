<?php 
session_start();

// Check if the user or admin is already logged in and redirect accordingly
if (isset($_SESSION["user"])) {
    header("Location: index.php");  // Redirect to the user index page
    exit();  // Ensure the script stops after redirect
} elseif (isset($_SESSION["admin"])) {
    header("Location: admin_dashboard.php");  // Redirect to admin dashboard
    exit();  // Ensure the script stops after redirect
}
if ($_SESSION['email_verified'] != 1) {
    header('Location: verify_email.php');
    exit;
}


// CSRF Token generation if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a new CSRF token
}

// Database connection
require_once "dbconnection.php";

// Display logout message if redirected from logout.php
$logoutMessage = "";
if (isset($_GET['message']) && $_GET['message'] == 'logged_out') {
    $logoutMessage = "You have successfully logged out.";
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token validity
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: login.php?error=csrf");
        exit();
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    // User login
    if (isset($_POST["login"])) {
        $sql = "SELECT * FROM users WHERE email = ?"; // Prepare SQL statement
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["id"]; // Store user ID in session
                header("Location: index.php"); // Redirect to index
                exit(); // Stop further execution after the redirect
            } else {
                echo "<div class='alert alert-danger'>Email or password is incorrect.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>SQL Error: " . mysqli_error($conn) . "</div>";
        }
    }

    // Admin login
    elseif (isset($_POST["admin_login"])) {
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $admin = mysqli_fetch_assoc($result);
            if (!$admin) {
                echo "<div class='alert alert-danger'>No admin found with this email.</div>";
            } else if ($admin && password_verify($password, $admin["password"])) {
                $_SESSION["admin"] = $admin["id"]; // Store admin ID in session
                $_SESSION["role"] = 'admin'; // Store role as 'admin'
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                exit(); // Stop further execution after the redirect
            } else {
                echo "<div class='alert alert-danger'>Email or password is incorrect.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>SQL Error: " . mysqli_error($conn) . "</div>";
        }
    }

    // User Registration
    elseif (isset($_POST["register"])) {
        $fullname = $_POST["fullname"];
        $email = $_POST["email"];
        $password_repeat = $_POST["repeat_password"];

        if ($password !== $password_repeat) {
            echo "<div class='alert alert-danger'>Passwords do not match.</div>";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hash password for storage

            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $password_hashed);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>Registration successful! You can now log in.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error during registration. Please try again.</div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
<style>
/* Basic Reset */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Roboto', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
.container { display: flex; justify-content: center; align-items: center; width: 100%; height: 100%; background:url("images/banner_1.jpg"); background-position: center; background-repeat: no-repeat; background-size: cover; }
.forms-container { width: 100%; max-width: 400px; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
h2 { text-align: center; margin-bottom: 20px; }
.input-group { margin-bottom: 20px; }
.input-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
.btn { width: 100%; padding: 12px; background-color: #667eea; color: #fff; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; transition: 0.3s; }
.btn:hover { background-color: #4e5ce6; }
.toggle-form { text-align: center; margin-top: 15px; color: #888; }
.toggle-form span { color: #667eea; cursor: pointer; }
.alert { text-align: center; margin-bottom: 20px; color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; }
.alert-danger { color: #721c24; background-color: #f8d7da; }
.alert-success { color: #155724; background-color: #d4edda; }
</style>

<div class="container">
    <div class="forms-container">

        <!-- Display logout message if set -->
        <?php if ($logoutMessage): ?>
            <div class="alert alert-success"><?php echo $logoutMessage; ?></div>
        <?php endif; ?>

        <!-- User Login Form -->
        <div class="login-form">
            <h2>User Login</h2>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <button type="submit" name="login" class="btn">Login</button>
                <p class="toggle-form">Don't have an account? <span id="switch-to-register">Register</span></p>
                <p class="toggle-form">Admin? <span id="switch-to-admin">Login as Admin</span></p>
            </form>
        </div>

        <!-- Admin Login Form -->
        <div class="admin-login-form" style="display: none;">
            <h2>Admin Login</h2>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <button type="submit" name="admin_login" class="btn">Login as Admin</button>
                <p class="toggle-form">Not an admin? <span id="switch-to-user-login">Login as User</span></p>
            </form>
        </div>

        <!-- User Registration Form -->
        <div class="register-form" style="display: none;">
            <h2>Register</h2>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <div class="input-group">
                    <input type="text" name="fullname" placeholder="Full Name" required />
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <div class="input-group">
                    <input type="password" name="repeat_password" placeholder="Confirm Password" required />
                </div>
                <button type="submit" name="register" class="btn">Register</button>
                <p class="toggle-form">Already have an account? <span id="switch-to-login">Login</span></p>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('switch-to-register').onclick = function() {
        document.querySelector('.login-form').style.display = 'none';
        document.querySelector('.register-form').style.display = 'block';
    };

    document.getElementById('switch-to-user-login').onclick = function() {
        document.querySelector('.register-form').style.display = 'none';
        document.querySelector('.login-form').style.display = 'block';
    };

    document.getElementById('switch-to-admin').onclick = function() {
        document.querySelector('.login-form').style.display = 'none';
        document.querySelector('.admin-login-form').style.display = 'block';
    };

    document.getElementById('switch-to-user-login').onclick = function() {
        document.querySelector('.admin-login-form').style.display = 'none';
        document.querySelector('.login-form').style.display = 'block';
    };
</script>

</body>
</html>