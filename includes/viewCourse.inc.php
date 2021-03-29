<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "dbc.inc.php";
require_once "coursesDbc.php";
require_once "coursesFunctions.php";
require_once "accountFunctions.inc.php";

$_SESSION["view_course"] = getCourse($conn,$_GET["id"]);
$_SESSION["view_course_subject"] = getSubject($conn,$_SESSION["view_course"]['subject_id']);
$_SESSION["view_course_teacher"] = getUser($conn,$_SESSION["view_course"]['teacher_id']);

header("location:../viewCourse.php");
exit();

