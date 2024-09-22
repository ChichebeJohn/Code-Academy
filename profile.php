<?php
session_start();

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

$username = $_SESSION['username'];

// Fetch user details
$user_sql = "SELECT firstname, lastname, username FROM users WHERE username='$username'";
$user_result = $conn->query($user_sql);

if ($user_result === false) {
    die("Error executing query: " . $conn->error);
}

$user_row = $user_result->fetch_assoc();
$first_name = $user_row['firstname'];
$last_name = $user_row['lastname'];
$user_name = $user_row['username'];
$full_name = $first_name . " " . $last_name;

// Fetch enrolled courses
$courses_sql = "SELECT courses.id, courses.course_name, courses.description, courses.video_url, enrollments.progress
                FROM enrollments
                JOIN courses ON enrollments.course_id = courses.id
                WHERE enrollments.username = '$username'";
$courses_result = $conn->query($courses_sql);

if ($courses_result === false) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Code Academy</title>
    <style>
        body {
            background-color: #101c28;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .header img {
            position: absolute;
            top: -25px;
            left: 10px;
            width: 150px;
            height: auto;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
        }
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #1e2a38;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header h2 {
            margin: 0;
            font-size: 28px;
        }
        .course-list {
            margin-top: 20px;
        }
        .course-item {
            background-color: #2b3b50;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .course-item h3 {
            margin: 0;
            color: #4CAF50;
        }
        .button-group {
            margin-top: 20px;
            text-align: center;
        }
        .button-group a, .button-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            margin: 0 10px;
            cursor: pointer;
        }
        .button-group a:hover, .button-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="CodeAcademy.png" alt="Code Academy Logo">
        <h1>Profile</h1>
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <h2><?php echo htmlspecialchars($full_name); ?></h2>
            <p>Username: <?php echo htmlspecialchars($user_name); ?></p>
        </div>

        <div class="course-list">
            <h3>Enrolled Courses</h3>
            <?php
            if ($courses_result->num_rows > 0) {
                while ($row = $courses_result->fetch_assoc()) {
                    echo "<div class='course-item'>";
                    echo "<h3>" . htmlspecialchars($row['course_name']) . "</h3>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
                    echo "<p><strong>Progress:</strong> " . htmlspecialchars($row['progress']) . "%</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No enrolled courses.</p>";
            }
            ?>
        </div>

        <div class="button-group">
            <a href="courses.php">Back to Courses</a>
            <button onclick="location.href='change_password.php'">Change Password</button>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
