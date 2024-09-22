<?php 
session_start();  // Start session to access user data

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

// Fetch courses from the database
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

// Check if the user has submitted the enroll form
if (isset($_POST['enroll'])) {
    $course_id = $_POST['course_id'];
    $username = $_SESSION['username'];
    
    // Check if user is already enrolled in this course
    $enroll_check_query = "SELECT * FROM enrollments WHERE username = '$username' AND course_id = '$course_id'";
    $enroll_check_result = $conn->query($enroll_check_query);
    
    if ($enroll_check_result->num_rows == 0) {
        // Insert enrollment into database
        $enroll_query = "INSERT INTO enrollments (username, course_id) VALUES ('$username', '$course_id')";
        if ($conn->query($enroll_query) === TRUE) {
            echo "<p style='color: green;'>Successfully enrolled in the course!</p>";
        } else {
            echo "<p style='color: red;'>Error enrolling: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>You are already enrolled in this course!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Code Academy</title>
    <style>
        body {
            background-color: #101c28;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
            font-family: 'Roboto', sans-serif;
        }
        .header nav {
            margin-top: 10px;
        }
        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        .header img {
            position: absolute;
            top: -25px;
            left: 10px;
            width: 150px;
            height: auto;
        }
        .menu-icon {
            display: none;
            font-size: 30px;
            color: red;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #333;
            width: 200px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .menu a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #444;
        }
        .menu a:hover {
            background-color: #555;
        }
        .course-list {
            margin: 20px;
            padding: 20px;
            background-color: #1e2a38;
            border-radius: 10px;
        }
        .course-item {
            margin-bottom: 20px;
        }
        .enroll-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .header nav {
                display: none;
            }
            .menu-icon {
                display: block;
            }
            .menu {
                display: none;
            }
        }
    </style>
    <script>
        function toggleMenu() {
            var menu = document.getElementById('menu');
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>

    <div class="header">
        <img src="CodeAcademy.png" alt="Code Academy Logo">
        <h1>Code Academy - Courses</h1>
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="my_courses.php">My Courses</a>
            <a href="profile.php">Profile</a>
            <a href="about_us.html">About Us</a>
            <a href="logout.php">Logout</a>
        </nav>
        <div id="menu" class="menu">
            <a href="home.php">Home</a>
            <a href="my_courses.php">My Courses</a>
            <a href="profile.php">Profile</a>
            <a href="about_us.html">About Us</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="course-list">
        <h2>Available Courses</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='course-item'>";
                echo "<h3>" . $row['course_name'] . "</h3>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='course_id' value='" . $row['id'] . "'>";
                echo "<button class='enroll-button' type='submit' name='enroll'>Enroll</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No courses available at the moment.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
