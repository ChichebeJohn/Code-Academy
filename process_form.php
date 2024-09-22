<?php 
session_start(); // Start the session to store user data

$servername = "localhost";
$db_username ="root";
$db_password = "";
$dbname ="code_academy";

// Creating connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection 
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Get form data and sanitize inputs
$firstname = $conn->real_escape_string(trim($_POST['firstname']));
$lastname = $conn->real_escape_string(trim($_POST['lastname']));
$username = $conn->real_escape_string(trim($_POST['username']));
$gender = $conn->real_escape_string(trim($_POST['gender']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password securely

// Check if email already exists 
$email_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$result = $conn->query($email_check_query);
$user = $result->fetch_assoc();

if ($user) { // If email exists
    echo "Email already registered. Proceed to <a href='login.html'>Login</a>.";
} else {
    // Insert data into the database
    $sql = "INSERT INTO users (firstname, lastname, username, email, gender, password) 
            VALUES ('$firstname', '$lastname', '$username', '$email', '$gender', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;

        // (Optional) "Remember Me" functionality:
        // If the form included a "Remember Me" checkbox, we can set a cookie with a token here
        
        // Redirect users to the course page after registration
        header("Location: courses.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
