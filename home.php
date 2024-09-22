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

// Define styling variables
$backgroundColor = '#101c28';
$textColor = '#ffffff';
$fontStyle = 'Arial, Helvetica, sans-serif';
$headerFontSize = '36px'; // Example font size for header
$navFontSize = '18px'; // Example font size for navigation links
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Code Academy</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        body {
            background-color: <?php echo $backgroundColor; ?>;
            color: <?php echo $textColor; ?>;
            font-family: <?php echo $fontStyle; ?>;
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
            font-size: <?php echo $headerFontSize; ?>;
            font-family: 'Roboto', sans-serif;
        }
        .header nav {
            margin-top: 10px;
        }
        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: <?php echo $navFontSize; ?>;
            font-family: 'Roboto', sans-serif;
        }
        .header img {
            position: absolute;
            top: -25px;
            left: 10px;
            width: 150px; /* Adjust size as needed */
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
        .welcome {
            text-align: center;
            margin: 50px 20px;
        }
        .welcome h2 {
            margin: 0 0 20px;
            font-size: 24px; /* Example size, adjust as needed */
        }
        .welcome p {
            font-size: 18px; /* Example size, adjust as needed */
        }
        .profile {
            text-align: center;
            margin: 20px 0;
        }
        .profile a {
            color: #ffffff;
            text-decoration: underline;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Responsive Styles */
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

    <!-- Header Section -->
    <div class="header">
        <img src="CodeAcademy.png" alt="Code Academy Logo">
        <h1>Code Academy</h1>
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="courses.php">Courses</a>
            <a href="about_us.html">About Us</a>
            <a href="contact_us.html">Contact Us</a>
            <a href="index.html">Quick quiz</a>
        </nav>
        <div id="menu" class="menu">
            <a href="home.php">Home</a>
            <a href="courses.php">Courses</a>
            <a href="about_us.html">About Us</a>
            <a href="contact_us.html">Contact Us</a>
            <a href="index.html">Quick quiz</a>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="welcome">
        <h2>Welcome, <?php echo $_SESSION['firstname']; ?>!</h2>
        <p>We are thrilled to have you at Code Academy. Whether you are looking to advance your career, explore new fields, or simply enhance your skills, you’ve come to the right place. Our diverse range of courses is designed to cater to various interests and career goals. Dive in and start your learning journey with us today!</p>
    </div>

    <!-- Profile Section -->
    <div class="profile">
        <a href="profile.php">View Profile</a>
    </div>

</body>
</html>

<?php 
$conn->close();
?>
