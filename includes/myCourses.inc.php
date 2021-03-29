<?php

session_start();

require_once 'dbc.inc.php';
require_once 'coursesFunctions.php';

if(isset($_SESSION['student_id'])) {
    if (isset($_POST['unsubscribe'])) {
        unSubscribe($conn,$_POST['unsubscribe'],$_SESSION["student_id"]);
        header("location: ../myCourses.php?error=successfullyUnsubscribed");
        exit(); // stop script from executing any further
    } else if(isset($_POST['remove'])) {
        removeSubscribedCourse($conn,$_POST['remove'],$_SESSION["student_id"]);
        header("location: ../myCourses.php?error=successfullyRemoved");
        exit(); // stop script from executing any further
    }
}else if(isset($_SESSION['teacher_id'])) {
    if (isset($_POST['edit'])) {
        $_SESSION["edited_course_id"] = $_POST['edit'];
        header("location: ../editCourse.php");
        exit(); // stop script from executing any further
    }else if (isset($_POST['delete'])) {
        deleteManagedCourse($conn,$_POST['delete']);
        header("location: ../myCourses.php?error=successfullyDeleted");
        exit(); // stop script from executing any further
    }else if (isset($_POST['create'])) {
        header("location: ../createCourse.php");
        exit(); // stop script from executing any further
    }
}else {
    header("location: ../myCourses.php");
    exit(); // stop script from executing any further
}

