<?php

session_start();
session_unset();
session_destroy();

// return to home page
header("location: ../home.php");
exit(); // stop script from executing any further
