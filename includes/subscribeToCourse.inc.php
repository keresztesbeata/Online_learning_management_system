<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "dbc.inc.php";
require_once "coursesDbc.php";
require_once "coursesFunctions.php";

if(isset($_POST['subscribe'])) {
    $subscription = getSubscription($conn, $_SESSION["view_course"]["course_id"], $_SESSION["student_id"]);
    if($subscription === false) {
        // not subscribed
        if(addSubscription($conn, $_SESSION["view_course"]["course_id"], $_SESSION["student_id"])) {
            header("location:../viewCourse.php?error=none");
            exit();
        }
    } else if(isExpiredSubscription($subscription["status_id"])) {
        if(renewSubscription($conn, $_SESSION["view_course"]["course_id"], $_SESSION["student_id"])) {
            header("location:../viewCourse.php?error=none");
            exit();
        }
        else {
            header("location:../viewCourse.php?error=failedToSubscribe");
            exit();
        }
    }else {
        header("location:../viewCourse.php?error=alreadySubscribed");
        exit();
    }
}
else {
    header("location:../viewCourse.php");
    exit();
}