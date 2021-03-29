<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'dbc.inc.php';
require_once 'coursesDbc.php';
require_once 'coursesFunctions.php';

if(isset($_POST['search'])) {
    // if no data was introduced update the account with the old values
    if (empty($_POST['name'])) {
        header("location: ../courses.php?error=emptyField");
        exit(); // stop script from executing any further
    } else {
        $name = $_POST['name'];
        $existingCourse = getCourseByName($conn, $name);
        if ($existingCourse === false) {
            header("location:../courses.php?error=noSuchCourse");
            exit();
        } else {
            $_SESSION["found_course"] = $existingCourse;
            header("location: ../courses.php?id=1");
            exit(); // stop script from executing any further
        }
    }
}else if(isset($_POST['filter'])) {
    if (isset($_POST['subject'])) {
        $_SESSION["filter_by_subject_id"] = $_POST['subject'];
        header("location: ../courses.php?id=2");
        exit(); // stop script from executing any further
    }
}else {
    header("location: ../courses.php");
    exit(); // stop script from executing any further
}