<?php
session_start();
?>
<?php
include_once 'header.php';
?>
<link rel="stylesheet" href="css/logFormStyle.css">

<title>Sign up</title>
</head>
<body>

<section>
    <div class="form">
        <form class="form-content" action="includes/signup.inc.php" method="post">
            <div class="container">
                <h1>Sign Up</h1>
                <hr>

                <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"] == "invalidEmail") {
                        echo "<p style='color: red'>Invalid email!</p>";
                    }else if($_GET["error"] == "duplicateUsername") {
                        echo "<p style='color: red'>This username or email is already used.<br>Please choose another one!</p>";
                    }else if($_GET["error"] == "databaseError") {
                        echo "<p style='color: red'>Account couldn't be created</p>";
                    }else if($_GET["error"] == "failedToPrepStmt") {
                        echo "<p style='color: red'>Failed to prep stmt</p>";
                    }else if($_GET["error"] == "saveUserError") {
                        echo "<p style='color: red'>Failed to create account</p>";
                    }else if($_GET["error"] == "saveStudentError") {
                        echo "<p style='color: red'>Failed to create Student account</p>";
                    }
                }
                ?>
                <label for="firstname"><b>First name</b>
                    <input type="text" placeholder="Enter First Name" name="firstname" required>
                </label>

                <label for="lastname"><b>Last name</b>
                    <input type="text" placeholder="Enter Last Name" name="lastname" required>
                </label>

                <label for="email"><b>Email</b>
                    <input type="text" placeholder="Enter Email" name="email" required>
                </label>

                <label for="username"><b>Username</b>
                    <input type="text" placeholder="Enter Username" name="username" required>
                </label>

                <label for="password"><b>Password</b>
                    <input type="password" placeholder="Enter Password" name="password" required>
                </label>

                <label><b>Are you a student/teacher?</b>
                    <select name="accountType">
                        <option value=1>Student</option>
                        <option value=2>Teacher</option>
                    </select>
                </label><br><br>
                    <button type="submit" class="submitButton" name="submit">Sign up</button>
                </div>

            </div>
        </form>
    </div>

</section>


</body>
</html>

