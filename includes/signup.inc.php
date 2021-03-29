<?php
if(isset($_POST['submit']) )
{
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $accountType = $_POST['accountType'];

    require_once 'dbc.inc.php';
    require_once 'accountFunctions.inc.php';

    signupUser($conn,$email,$username,$password,$firstname,$lastname,$accountType);

}else {
    header("location: ../signup.php");
    exit(); // stop script from executing any further
}
