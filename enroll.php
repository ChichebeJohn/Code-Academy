<?php 
session_start();  // Start the session to access stored user data

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "code_academy";

// Create connection 
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$course_id = $_POST['course_id'];
$username = $_SESSION['username'];

// Insert enrollment into the database
$sql = "INSERT INTO enrollments (username, course_id) VALUES ('$username', '$course_id')";

if ($conn->query($sql) === TRUE) {
    echo "You have been enrolled in the course successfully! <a href='courses.php'>Back to courses</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
