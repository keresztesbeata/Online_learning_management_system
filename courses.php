<?php
session_start();

include_once 'header.php';
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/coursesStyle.css">

<title>Courses</title>

</head>

<body>

<form action='includes/filterCourses.inc.php' method='post'>
    <?php
    if(isset($_GET["error"])) {
        if($_GET["error"] == "emptyField") {
            echo "<p class='warningMessage'>You need to introduce a valid name!</p>";
        }if($_GET["error"] == "noSuchCourse") {
            echo "<p class='warningMessage'>No course was found with the given name!</p>";
        }else if($_GET["error"] == "failedToPrepareStatement") {
            echo "<p class='warningMessage'>Database error.";
        }
    }
    ?>
    <label for="name"><b>Search for a specific course:</b>
    <input type="text" placeholder="Enter name of the course" name="name">
    </label>
    <button type='submit' name='search'>Search</button><br>

    <label for="subject"><b>Search for courses of a specific subject:</b>
            <select name="subject">
                <?php
                require_once "includes/dbc.inc.php";
                require_once "includes/coursesDbc.php";
                $resultData = getAllSubjects($conn);
                $nrRows = mysqli_num_rows($resultData);
                if(!isset($_SESSION["filter_by_subject_id"]) || $_SESSION["filter_by_subject_id"] == 0) {
                    echo " <option value= 0 selected>" . "Any subject" . "</option>";
                }else {
                    echo " <option value=0>" . "Any subject" . "</option>";
                    echo " <option value=" . $_SESSION["filter_by_subject_id"] . " selected>" . getSubject($conn, $_SESSION["filter_by_subject_id"])["name"] . "</option>";
                }
                for($i = 1; $i <= $nrRows; $i++) {
                    $row = mysqli_fetch_array($resultData);
                        if($row["id"] != $_SESSION["filter_by_subject_id"]) {
                            echo " <option value=" . $row["id"] . ">" . $row["name"] . "</option>";
                        }
                }
                ?>
            </select>
    </label>
    <button type='submit' name='filter'>Show Courses</button><br>

</form>
<br>
<table>
    <tr>
        <th>
            Course name
        </th>
        <th>
            Subject
        </th>
        <th>
            Level
        </th>
        <th>
            Teacher
        </th>
        <th>
            Link to Course
        </th>
    </tr>
<?php

require_once "includes/dbc.inc.php";
require_once "includes/coursesDbc.php";
require_once "includes/coursesFunctions.php";

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if($id == 1) {
        printFoundCourse($conn,$_SESSION["found_course"]);
    }else if($id == 2) {
        if ($_SESSION["filter_by_subject_id"] > 0) {
            $resultData = getCoursesOfSubject($conn, $_SESSION["filter_by_subject_id"]);
        } else {
            $resultData = getAllCourses($conn);
        }
        printCourses($conn, $resultData);
    }
}else {
    $resultData = getAllCourses($conn);
    printCourses($conn,$resultData);
}
?>
</table>

</body>
</html>




