<?php
session_start();

require_once 'dbc.inc.php';
require_once 'accountFunctions.inc.php';


if(isset($_POST['save']) )
{
    // if no data was introduced update the account with the old values
        if (empty($_POST['email'])) {
            $email = $_SESSION["user"]["email"];
        } else {
            // check for valid and unique email
            $email = $_POST['email'];
            if(!isValidEmail($email)) {
                header("location: ../myAccount.php?error=invalidEmail");
                exit(); // stop script from executing any further
            }
            if($email != $_SESSION["user"]["email"] && userExists($conn,$email,$email) !== false) {
                header("location: ../myAccount.php?error=duplicateUsername");
                exit(); // stop script from executing any further
            }
        }

        if (empty($_POST['password'])) {
            $password = $_SESSION["user"]["user_password"];
        } else {
            $password = $_POST['password'];
        }

        if (empty($_POST['username'])) {
            $username = $_SESSION["user"]["username"];
        } else {
            // check for username duplications
            $username = $_POST['username'];
            if($username != $_SESSION["user"]["username"] && userExists($conn,$username,$username) !== false) {
                header("location: ../myAccount.php?error=duplicateUsername");
                exit(); // stop script from executing any further
            }

        }

        if (empty($_POST['firstname'])) {
            $firstname = $_SESSION["user"]["first_name"];
        } else {
            $firstname = $_POST['firstname'];
        }

        if (empty($_POST['lastname'])) {
            $lastname = $_SESSION["user"]["last_name"];
        } else {
            $lastname = $_POST['lastname'];
        }

        // update the account of the user with the new data
        if(updateUserAccount($conn, $email, $username, $password, $firstname, $lastname) === false) {
            header("location:../myAccount.php?error=updateFailedError");
            exit();
        }

    // check if it is a student account and update the necessary fields
    if(isset($_SESSION["student"])) {
        if (empty($_POST['faculty'])) {
            $faculty = $_SESSION["student"]["faculty"];
        } else {
            $faculty = $_POST['faculty'];
        }
        if (empty($_POST['graduateType'])) {
            $graduateTypeId = $_SESSION["student"]["graduate_type_id"];
        } else {
            $graduateTypeId = $_POST['graduateType'];
        }
        if (updateStudentAccount($conn, $faculty, $graduateTypeId) === false) {
            header("location:../myAccount.php?error=updateFailedError");
            exit();
        } else {
            header("location:../myAccount.php?error=accountSaved");
            exit();
        }
    }

    // check if it is a teacher account and update the necessary fields
    if(isset($_SESSION["teacher"])) {
        if (empty($_POST['specialty'])) {
            $specialty = $_SESSION["teacher"]["specialty"];
        } else {
            $specialty = $_POST['specialty'];
        }
        if (updateTeacherAccount($conn, $specialty) === false) {
            header("location:../myAccount.php?error=updateFailedError");
            exit();
        } else {
            header("location:../myAccount.php?error=accountSaved");
            exit();
        }
    }
}else if(isset($_POST['delete'])) {
        //deleteAccount($conn);
        if(deleteAccount($conn) === true) {
            // redirect to home page
            header("location: ../myAccount.php?error=accountDeleted");
            exit(); // stop script from executing any further
        }else {
            header("location: ../myAccount.php?error=failedDeleteError");
            exit(); // stop script from executing any further
        }
    }
else {
    header("location: ../myAccount.php");
    exit(); // stop script from executing any further
}
