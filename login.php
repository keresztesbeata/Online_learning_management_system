<?php
session_start();
?>
<?php
include_once 'header.php';
?>
    <link rel="stylesheet" href="css/logFormStyle.css">
    <title>Log in</title>
</head>
<body>

<section>
    <div class="form">
        <form class="form-content" action="includes/login.inc.php" method="post">
            <div class="container">
                <h1>Log In</h1>
                <hr>
                <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"] == "wrongUsername") {
                        echo "<p style='color: red'>Invalid username or email!</p>";
                    }else if($_GET["error"] == "wrongPassword") {
                        echo "<p style='color: red'>Invalid password!</p>";
                    }else if($_GET["error"] == "failedStmt") {
                        echo "<p style='color: red'>Database error</p>";
                    }
                }
                ?>
                <label for="username"><b>Username</b>
                    <input type="text" placeholder="Enter Username/Email" name="username" required>
                </label>

                <label for="password"><b>Password</b>
                <input type="password" placeholder="Enter Password" name="password" required>
                </label>

                <div>
                    <button type="submit" class="submitButton" name="submit">Log in</button>
                </div>

            </div>
        </form>
    </div>

</section>


</body>
</html>


