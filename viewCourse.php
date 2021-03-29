<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include_once 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/viewCourseStyle.css">

<title>View Course</title>
</head>

<body>

<div class = "container">
    <?php
    if(isset($_GET["error"])) {
        if ($_GET["error"] == "failedToSubscribe") {
            echo "<p class='warningMessage'>Failed to subscribe!</p>";
        }else if ($_GET["error"] == "alreadySubscribed") {
            echo "<p class='warningMessage'>You are already subscribed to this course!</p>";
        }else if ($_GET["error"] == "none") {
            echo "<p class='message'>You have successfully subscribed to this course!</p>";
        }
    }
    ?>
<table>
    <tr>
        <td class="tdLabel"> Name:</td>
        <td>
            <?php
            if(isset($_SESSION['view_course'])) {
            echo $_SESSION['view_course']['name'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="tdLabel"> Subject:</td>
        <td>
            <?php
            if(isset($_SESSION['view_course_subject'])) {
                echo $_SESSION['view_course_subject']['name'];
            }
            ?>
        </td>
    </tr>
    <tr>
    <td class="tdLabel"> Level:</td>
    <td>
        <?php
        require_once "includes/dbc.inc.php";
        require_once "includes/coursesDbc.php";
        if(isset($_SESSION['view_course'])) {
            echo getSkillLevel($conn,$_SESSION['view_course']['skill_level_id']);
        }
        ?>
    </td>
    </tr>
    <tr>
        <td class="tdLabel">Teacher:</td>
        <td>
            <?php
            if(isset($_SESSION['view_course_teacher'])) {
                echo $_SESSION['view_course_teacher']['first_name'] . " " . $_SESSION['view_course_teacher']['last_name'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="tdLabel">Duration: </td>
        <td>
            <?php
            if(isset($_SESSION['view_course'])) {
                echo $_SESSION['view_course']['duration']. " months";
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="tdLabel">Description: </td>
        <td>
            <?php
            if(isset($_SESSION['view_course'])) {
                echo $_SESSION['view_course']['description'];
            }
            ?>
        </td>
    </tr>
</table>
    <?php
    if(isset($_SESSION["student_id"])) {
        echo "<form action='includes/subscribeToCourse.inc.php' method='post'>
            <button type='submit' name='subscribe'>Subscribe</button>
        </form>";
    }
    ?>
</div>
</body>
</html>

