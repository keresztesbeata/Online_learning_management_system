<?php
session_start();
?>
<?php
include_once 'header.php';
?>
<link rel="stylesheet" href="css/logFormStyle.css">

<title>Create new course</title>
</head>
<body>

<section>
    <div class="form">
        <form class="form-content" action="includes/createCourse.inc.php" method="post">
            <div class="container">
                <h1>Create course</h1>
                <hr>
                <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"] == "failedToCreateCourse") {
                        echo "<p class = 'warningMessage'>Failed to create course!</p>";
                    }else if($_GET["error"] == "failedToPrepareStatement") {
                        echo "<p class = 'warningMessage'>Database error.";
                    }if($_GET["error"] == "duplicateName") {
                        echo "<p class = 'warningMessage'>Another course with the same name already exists!<br>Please choose another name!</p>";
                    }else if($_GET["error"] == "successfullySaved") {
                        echo "<p class = 'message'>The course was successfully saved!</p>";
                    }
                }
                ?>
                <label for="Name"><b>Name</b>
                    <input type="text" placeholder="Name..." name="name" required>
                </label>

                        <label><b>Select the subject:</b>
                                <select name="subject" required>
                                    <?php
                                    require_once "includes/dbc.inc.php";
                                    require_once "includes/coursesDbc.php";
                                    $resultData = getAllSubjects($conn);
                                    $nrRows = mysqli_num_rows($resultData);
                                    for($i = 1; $i <= $nrRows; $i++) {
                                        $row = mysqli_fetch_array($resultData);
                                        echo " <option value=". $row["id"]. ">". $row["name"] ."</option>";
                                    }
                                    ?>
                                </select>
                        </label><br><br>

                    <label><b>Select the required skill level:</b>
                        <select name="skillLevel" required>
                            <?php
                            require_once "includes/dbc.inc.php";
                            require_once "includes/coursesDbc.php";
                            $resultData = getAllSkillLevels($conn);
                            $nrRows = mysqli_num_rows($resultData);
                            for($i = 1; $i <= $nrRows; $i++) {
                                $row = mysqli_fetch_array($resultData);
                                echo " <option value=". $i. ">". $row["level"] ."</option>";
                            }
                            ?>
                        </select>
                    </label><br><br>

                <label for="duration"><b>Duration</b>
                    <input type="text" placeholder="Duration(in months)..." name="duration" required>
                </label><br>

                <label for="description"><b>Description</b><br>
                    <textarea rows = "5" placeholder="Short description..." name="description"></textarea>
                </label><br>

                <label><b>Do you want to provide a certificate of completion?</b>
                        <select name="certificate">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                </label><br><br>

               <div>
                   <button type="submit" class="submitButton" name="save">Save</button>
               </div>
            </div>
    </div>
    </form>
    </div>

</section>


</body>
</html>


