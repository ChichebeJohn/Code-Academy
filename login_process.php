<?php
session_start(); // Start the session

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "code_academy";

// Creating connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['rememberMe']); // Check if "Remember Me" is checked

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['username'] = $user['username'];
    $_SESSION['firstname'] = $user['firstname'];
    $_SESSION['lastname'] = $user['lastname'];

    // Handle "Remember Me" feature
    if ($remember) {
        // Generate a unique token
        $token = bin2hex(random_bytes(16));

        // Store the token in the database for this user
        $token_query = "UPDATE users SET remember_token='$token' WHERE email='$email'";
        $conn->query($token_query);

        // Store the token in a cookie for 30 days
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), "/"); // 30 days
    }

    // Redirect to the landing page (courses.php)
    header("Location: courses.php");
    exit();
} else {
    echo "Invalid email or password. Please try again.";
}

$conn->close();
?>
