<?php

require_once 'accountsDbc.php';

/**
 * Allows the user to log in
 * @param $conn = connection to the db
 * @param $username = introduced by the user
 * @param $password = password introduced by the user
 */
function loginUser($conn,$username,$password) {
    $user = userExists($conn,$username,$username);

    if($user === false) {
        header("location: ../login.php?error=wrongUsername");
        exit();
    }

    $user_password = $user["user_password"];
    $checkPassword = $password == $user_password;

    if($checkPassword === false) {
        header("location: ../login.php?error=wrongPassword");
        exit();
    }else if($checkPassword === true) {
        // start a session to remember the user's id as long as he/she is logged in
        session_start();
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user"] = $user;
        $student = getStudent($conn,$user["user_id"]);
        if( $student !== false && $student !== null) {
            $_SESSION["student_id"] = $_SESSION["user_id"];
            $_SESSION["student"] = $student;
        }else {
            $teacher = getTeacher($conn,$user["user_id"]);
            if($teacher !== false && $teacher !== null) {
                $_SESSION["teacher_id"] = $_SESSION["user_id"];
                $_SESSION["teacher"] = $teacher;
            }
        }
        // go to the home page
        header("location: ../home.php");
        exit();
    }
}

/**
 * Check if the format of the email is valid.
 */
function isValidEmail($email) {
    return filter_var($email,FILTER_VALIDATE_EMAIL);
}

/**
 * Check if the data introduced by the user for the sign-up is valid:
 * <p>the email has a valid format</p>
 * <p>the username is unique, no duplications</p>
 * @param $conn = connection to db
 * @param $email = email address given
 * @param $username = username given
 */
function validateSignupData($conn,$email,$username) {
    // check if email is valid
    if(isValidEmail($email) === false) {
        header("location: ../signup.php?error=invalidEmail");
        exit();
    }
    // check if a user already exists with same username or email
    $userExists = userExists($conn,$username,$email);
    if($userExists !== false) {
        header("location: ../signup.php?error=duplicateUsername");
        exit();
    }

}

/**
 * Sign up the user to the application.
 */
function signupUser($conn,$email,$username,$password,$firstname,$lastname,$accountType) {
    validateSignupData($conn,$email,$username);
    if(createUser($conn,$email,$username,$password,$firstname,$lastname)===true) {
        $user = userExists($conn, $username,$email);
        $userid = $user["user_id"];
        if ($accountType == 1) {
            createStudent($conn,$userid);
            $student = getStudent($conn,$userid);
        }else if($accountType == 2) {
            createTeacher($conn,$userid);
            $teacher = getTeacher($conn,$userid);
        }
        session_start();
        $_SESSION["user_id"] = $userid;
        $_SESSION["user"] = $user;
        if($accountType == 1) {
            $_SESSION["student_id"] = $userid;
            $_SESSION["student"] = $student;
        }else if($accountType==2) {
            $_SESSION["teacher_id"] = $userid;
            $_SESSION["teacher"] = $teacher;
        }
        // go to the home page
        header("location: ../home.php");
        exit();
    }else {
        header("location: ../signup.php?error=saveUserError");
        exit();
    }
}
/** update the new data for the currently logged in user */
function updateUserAccount($conn,$email,$username,$password,$firstname,$lastname) {

    if(updateUser($conn,$email,$username,$password,$firstname,$lastname)) {
        $_SESSION["user"]=getUser($conn,$_SESSION["user_id"]);
        return true;
    }else {
        return false;
    }
}
/** update the student account with the new data */
function updateStudentAccount($conn,$faculty,$graduateTypeId) {
    if(updateStudent($conn,$faculty,$graduateTypeId) === true) {
        $_SESSION["student"]=getStudent($conn,$_SESSION["student_id"]);
        return true;
    }else {
        return false;
    }
}

/** update the teacher account with the new data */
function updateTeacherAccount($conn,$specialty) {
    if(updateTeacher($conn,$specialty) === true) {
        $_SESSION["teacher"]=getTeacher($conn,$_SESSION["teacher_id"]);
        return true;
    }else {
        return false;
    }
}

/** delete currently logged-in user's account */
function deleteAccount($conn) {
    // first delete student/teacher account
    if(isset($_SESSION["student_id"])) {
        deleteStudent($conn);
    }else if(isset($_SESSION["teacher_id"])) {
        deleteTeacher($conn);
    }
    // then delete the user
    if(isset($_SESSION["user_id"])) {
        deleteUser($conn);
        session_unset();
        session_destroy();
        return true;
    }
    return false;
}