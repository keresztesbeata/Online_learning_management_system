<?php

if(isset($_POST["submit"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    require_once 'dbc.inc.php';
    require_once 'accountFunctions.inc.php';

    loginUser($conn,$username,$password);

}else {
    header("location: ../login.php");
    exit(); // stop script from executing any further
}
