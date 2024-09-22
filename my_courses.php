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

// Fetch the enrolled courses for the logged-in user
$username = $_SESSION['username'];

// Modified SQL query for enrolled courses
$sql = "SELECT courses.id, courses.course_name, courses.description, courses.duration, courses.video_url, enrollments.progress
        FROM enrollments 
        JOIN courses ON enrollments.course_id = courses.id 
        WHERE enrollments.username = '$username'";

// Execute the query and check for errors
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Unenroll functionality
if (isset($_POST['unenroll'])) {
    $course_id = $_POST['course_id'];
    $delete_sql = "DELETE FROM enrollments WHERE course_id='$course_id' AND username='$username'";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: my_courses.php"); // Refresh the page after unenrolling
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Complete course functionality
if (isset($_POST['complete_course'])) {
    $course_id = $_POST['course_id'];
    $update_sql = "UPDATE enrollments SET progress=100 WHERE course_id='$course_id' AND username='$username'";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: my_courses.php"); // Refresh the page after updating progress
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Code Academy</title>
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
            position: relative;
        }
        .header img {
            position: absolute;
            top: -25px;
            left: 10px;
            width: 150px; /* Adjust size as needed */
            height: auto;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
        }
        .menu-icon {
            display: none;
            font-size: 28px;
            color: red;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .menu {
            display: none;
            background-color: #333;
            position: absolute;
            top: 50px;
            right: 0;
            width: 200px;
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
            background-color: #2b3b50;
            padding: 10px;
            border-radius: 5px;
        }
        .course-item h3 {
            margin: 0;
            color: #4CAF50;
        }
        .progress-container {
            margin-top: 10px;
            background-color: #444;
            border-radius: 5px;
            height: 20px;
            position: relative;
        }
        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            border-radius: 5px;
            position: absolute;
            bottom: 0;
            left: 0;
        }
        .button-group {
            margin-top: 10px;
        }
        .button-group button, .button-group form {
            margin-right: 10px;
        }
        .details, .unenroll, .view-course, .complete-course, .completed {
            background-color: #ff5722;
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }
        .details {
            background-color: #2196f3;
        }
        .view-course {
            background-color: #4CAF50;
        }
        .complete-course {
            background-color: #4CAF50;
        }
        .complete-course:hover {
            background-color: #45a049; /* Darker green */
        }
        .completed {
            background-color: #9e9e9e;
            cursor: not-allowed;
        }
        .course-description {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #444;
            border-radius: 5px;
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

        function toggleDetails(courseId) {
            var descriptionDiv = document.getElementById('description-' + courseId);
            if (descriptionDiv.style.display === 'none') {
                descriptionDiv.style.display = 'block';
            } else {
                descriptionDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>

    <div class="header">
        <img src="CodeAcademy.png" alt="Code Academy Logo">
        <h1>My Courses</h1>
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
        <nav>
            <a href="home.php">Home</a> |
            <a href="courses.php">Courses</a> |
            <a href="profile.php">Profile</a>
        </nav>
        <div id="menu" class="menu">
            <a href="home.php">Home</a>
            <a href="courses.php">Courses</a>
            <a href="profile.php">Profile</a>
        </div>
    </div>

    <div class="course-list">
        <h2>Your Enrolled Courses</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $isCompleted = $row['progress'] == 100;
                echo "<div class='course-item'>";
                echo "<h3>" . $row['course_name'] . "</h3>";
                // Course progress bar
                $progress = isset($row['progress']) ? $row['progress'] : 0;
                echo "<div class='progress-container'><div class='progress-bar' style='width: " . $progress . "%;'></div></div>";
                // Button group
                echo "<div class='button-group'>";
                if (!$isCompleted) {
                    echo "<button class='details' onclick='toggleDetails(" . $row['id'] . ")'>Show Details</button>";
                    echo "<form method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='course_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='complete_course' class='complete-course'>Complete Course</button>";
                    echo "</form>";
                } else {
                    echo "<button class='completed' disabled>Completed</button>";
                }
                // View Course button
                $video_url = $row['video_url'];
                if (!empty($video_url)) {
                    echo "<a href='$video_url' target='_blank'><button class='view-course'>View Course</button></a>";
                }
                // Unenroll button
                echo "<form method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='course_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='unenroll' class='unenroll'>Unenroll</button>";
                echo "</form>";
                echo "</div>";
                // Details section
                echo "<div class='course-description' id='description-" . $row['id'] . "'>";
                echo "<p><strong>Course Description:</strong> " . $row['description'] . "</p>";
                echo "<p><strong>Duration:</strong> " . $row['duration'] . " weeks</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>You are not enrolled in any courses yet. Head over to <a href='courses.php' style='color: #4CAF50;'>Courses</a> to enroll.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
