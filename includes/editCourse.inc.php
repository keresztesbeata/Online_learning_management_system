<?php

session_start();

require_once 'dbc.inc.php';
require_once 'coursesDbc.php';
require_once 'coursesFunctions.php';

if(isset($_POST['save'])) {
    // if no data was introduced update the account with the old values
    if (empty($_POST['name'])) {
        $name = $_SESSION["edited_course"]["name"];
    } else {
        // check for unique name
        $name = $_POST['name'];
        if(getCourseByName($conn,$name) !== false) {
            header("location: ../editCourse.php?error=duplicateName");
            exit(); // stop script from executing any further
        }
    }

    if (!isset($_POST['subject'])) {
        $subjectId = $_SESSION["edited_course"]["subject_id"];
    } else {
        $subjectId = $_POST['subject'];
    }

    if (!isset($_POST['skillLevel'])) {
        $skillLevelId = $_SESSION["edited_course"]["skill_level_id"];
    } else {
        $skillLevelId = $_POST['skillLevel'];
    }

    if (empty($_POST['duration'])) {
        $duration = $_SESSION["edited_course"]["duration"];
    } else {
        $duration = $_POST['duration'];
    }

    if (empty($_POST['description'])) {
        $description = $_SESSION["edited_course"]["description"];
    } else {
        $description = $_POST['description'];
    }

    if (!isset($_POST['certificate'])) {
        $certificate = $_SESSION["edited_course"]["certificate_of_completion"];
    } else {
        $certificate = $_POST['certificate'];
    }

    if(!updateCourse($conn,$name,$subjectId,$_SESSION["teacher_id"],$skillLevelId,$duration,$description,$certificate,$_SESSION["edited_course_id"])) {
        header("location: ../editCourse.php?error=failedToUpdateCourse");
        exit(); // stop script from executing any further
    }else {
        // update the course whose information is displayed
        $_SESSION["edited_course"] = getCourse($conn,$_SESSION["edited_course_id"]);
        header("location: ../editCourse.php?error=successfullyUpdated");
        exit(); // stop script from executing any further
    }
}else {
    header("location: ../editCourse.php");
    exit(); // stop script from executing any further
}
