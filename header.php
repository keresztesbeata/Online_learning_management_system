
<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" href="css/topnavStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <nav id="menubar">
            <a class="links" id="home_link" href="home.php">Home</a>
            <a class="links" id="courses_link" href="courses.php">Courses</a>
            <?php
            if(isset($_SESSION["user_id"])) {
                echo "<a class='links' id='mycourses_link' href='myCourses.php'>My Courses</a>";
                echo "<a class='links' id='myaccount_link' href='myAccount.php'>My Account</a>";
                echo "<a class='links' id='logout_link' href='includes/logout.inc.php'>Log Out</a>";
            }else {
                echo "<a class='links' id='login_link' href='login.php'>Log In</a>";
                echo "<a class='links' id='signup_link' href='signup.php'>Sign Up</a>";
            }
            ?>
    </nav>
