<?php

session_start();

require_once 'dbc.inc.php';
require_once 'coursesDbc.php';

if(isset($_POST['save'])) {
    $name = $_POST['name'];
    $row = getCourseByName($conn,$name);
    // check for unique name
    $name = $_POST['name'];
    if(getCourseByName($conn,$name) !== false) {
        header("location: ../editCourse.php?error=duplicateName");
        exit(); // stop script from executing any further
    }
    $subjectId = $_POST['subject'];
    $skillLevelId = $_POST['skillLevel'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $certificate = $_POST['certificate'];
    if(!createCourse($conn,$name,$subjectId,$_SESSION["teacher_id"],$skillLevelId,$duration,$description,$certificate)) {
        header("location: ../createCourse.php?error=failedToCreateCourse");
    }else {
        header("location: ../createCourse.php?error=successfullySaved");
    }
    exit(); // stop script from executing any further
}else {
    header("location: ../createCourse.php");
    exit(); // stop script from executing any further
}
