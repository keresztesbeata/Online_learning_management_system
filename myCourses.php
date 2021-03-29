<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include_once 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/myCoursesTableStyle.css">

<title>My Courses</title>
</head>

<body>

<div class = "container">
    <?php
    if(isset($_GET["error"])) {
        if ($_GET["error"] == "failedToUnsubscribe") {
            echo "<p class='warningMessage'>Failed to unsubscribe!</p>";
        }else if ($_GET["error"] == "successfullyUnsubscribed") {
            echo "<p class='message'>Your have successfully unsubscribed from the course!</p>";
        }if ($_GET["error"] == "failedGettingCourses") {
            echo "<p class='warningMessage''>Failed to get your courses!</p>";
        }else if ($_GET["error"] == "noCourses") {
            echo "<p class='warningMessage''>You have not subscribed to any course yet!</p>";
        }else if ($_GET["error"] == "successfullyRemoved") {
            echo "<p class='message''>The course has been successfully removed from your subscription list!</p>";
        }else if ($_GET["error"] == "successfullyDeleted") {
            echo "<p class='message'>The course has been successfully deleted!</p>";
        }
    }?>
    <table>
        <?php
        require_once 'includes/dbc.inc.php';
        require_once 'includes/coursesFunctions.php';
        if(isset($_SESSION["student_id"])) {
            printSubscribedCourses($conn,$_SESSION["student_id"]);
        }else if(isset($_SESSION["teacher_id"])) {
                printManagedCourses($conn,$_SESSION["teacher_id"]);
            }
        ?>
    </table>
    <?php
    if(isset($_SESSION["teacher_id"])) {
        echo "<form action='includes/myCourses.inc.php' method='post'>
            <button class='createButton' type='submit' name='create'>Create new Course</button>
        </form>";
    }
    ?>
</div>
</body>
</html>

