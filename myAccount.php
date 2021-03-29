<?php
session_start();
?>
<?php
include_once 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/myAccountFormStyle.css">

<title>Online Learning Management System</title>

</head>

<body>

<section>
    <div class="form">
        <div style = 'margin-left:30%;width:40%;'>
        <h2 align="center">Account details</h2>
        <h4 align="center">You can edit your account information here.</h4>
        </div>
        <hr>
        <form class="form-content" action="includes/myAccount.inc.php" method="post">
            <div class="container">
                <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"] == "invalidEmail") {
                        echo "<p class='warningMessage'>Invalid email!</p>";
                    }else if($_GET["error"] == "duplicateUsername") {
                        echo "<p class='warningMessage'>This username or email is already used.<br>Please choose another one!</p>";
                    }else if($_GET["error"] == "databaseError") {
                        echo "<p class='warningMessage'>Account couldn't be created</p>";
                    }else if($_GET["error"] == "failedToPrepStmt") {
                        echo "<p class='warningMessage'>Failed to prep stmt</p>";
                    }else if($_GET["error"] == "updateUserError") {
                        echo "<p class='warningMessage'>Failed to create account</p>";
                    }else if($_GET["error"] == "updateFailedError") {
                        echo "<p class='warningMessage'>Update failed!</p>";
                    }else if($_GET["error"] == "accountSaved") {
                        echo "<p class='message'>Your data has been successfully saved!</p>";
                    }else if($_GET["error"] == "failedDeleteError") {
                        echo "<p class='warningMessage'>Your account couldn't be deleted!</p>";
                    }else if($_GET["error"] == "accountDeleted") {
                        echo "<p class='message'>Your account has been deleted.</p>";
                    }
                }
                ?>
                <label for="firstname"><b>First name</b>
                    <input type="text" placeholder="<?php
                    if(isset($_SESSION["user"])) {
                        echo $_SESSION["user"]["first_name"];
                    }
                    ?>" name="firstname" class="account_data">
                </label>

                <label for="lastname"><b>Last name</b>
                    <input type="text" placeholder="<?php
                    if(isset($_SESSION["user"])) {
                        echo $_SESSION["user"]["last_name"];
                    }
                    ?>" name="lastname">
                </label>

                <label for="email"><b>Email</b>
                    <input type="text" placeholder="<?php
                    if(isset($_SESSION["user"])) {
                        echo $_SESSION["user"]["email"];
                    }
                    ?>" name="email">
                </label>

                <label for="username"><b>Username</b>
                    <input type="text" placeholder="<?php
                    if(isset($_SESSION["user"])) {
                        echo $_SESSION["user"]["username"];
                    }
                    ?>" name="username">
                </label>

                <label for="password"><b>Password</b>
                    <input type="password" placeholder="<?php
                    if(isset($_SESSION["user"])) {
                        echo $_SESSION["user"]["user_password"];
                    }
                    ?>" name="password">
                </label>

                <?php
                if(isset($_SESSION["student_id"])) {
                    $faculty = $_SESSION["student"]["faculty"];

                    echo "<label for='graduateType'><b>Graduate type:</b>
                        <select name='graduateType' required>";
                           require_once 'includes/dbc.inc.php';
                            require_once 'includes/accountsDbc.php';
                            $resultData = getAllGraduateTypes($conn);
                            $nrRows = mysqli_num_rows($resultData);
                            for($i = 1; $i <= $nrRows; $i++) {
                                $row = mysqli_fetch_array($resultData);
                                if($row["id"] == $_SESSION["student"]["graduate_type_id"]) {
                                    echo " <option value=" . $row["id"] . " selected>" . $row["name"] . "</option>";
                                }else {
                                    echo " <option value=" . $row["id"] . ">" . $row["name"] . "</option>";}
                            }
                    echo "</select>
                    </label><br><br>
                    </label>
                    <label for='faculty'><b>Faculty</b>
                         <input type='text' placeholder='$faculty' name='faculty' >
                    </label>";
                }
                else if(isset($_SESSION["teacher_id"])) {
                    $specialty = $_SESSION["teacher"]["specialty"];

                    echo "<label for='specialty'><b>Specialty</b>
                                    <input type='text' placeholder='$specialty' name='specialty' >
                                </label>";
                }
                ?>
                <div><button type="submit" class="submitButton" name="save">Save</button></div>
                <div><button type="submit" class="submitButton" name="delete">Delete Account</button></div>

    </div>
    </form>
    </div>

</section>


</body>
</html>
