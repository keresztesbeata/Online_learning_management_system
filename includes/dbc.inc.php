<?php

$hostName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "olms";

$conn = mysqli_connect($hostName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed:" . mysqli_connect_error());
}
