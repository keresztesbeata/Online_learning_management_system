<?php
session_start();

require_once "includes/dbc.inc.php";
require_once "includes/coursesDbc.php";
$_SESSION["edited_course"] = getCourse($conn,$_SESSION["edited_course_id"]);

include_once 'header.php';
?>
<link rel="stylesheet" href="css/logFormStyle.css">

<title>Edit course</title>
</head>
<body>

<section>
    <div class="form">
        <form class="form-content" action="includes/editCourse.inc.php" method="post">
            <div class="container">
                <div style = 'margin-left:30%;width:40%;'>
                    <p align="center" style="font-size:20px"><b>Edit Course details</b></p>
                <p align="center">You can edit the details of this course here.</p>
                </div>
                <hr>

                <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"] == "failedToUpdateCourse") {
                        echo "<p class='warningMessage'>Failed to update course!</p>";
                    }if($_GET["error"] == "duplicateName") {
                        echo "<p class='warningMessage'>There already exists a course with the given name!<br>Please choose another one!</p>";
                    }else if($_GET["error"] == "failedToPrepareStatement") {
                        echo "<p class='warningMessage'>Database error.";
                    }else if($_GET["error"] == "successfullyUpdated") {
                        echo "<p class='message'>The course was successfully updated!</p>";
                    }
                }
                ?>
                <label for="Name"><b>Name</b>
                    <input type="text" placeholder="<?php echo $_SESSION["edited_course"]["name"];?>" name="name">
                </label>

                    <label><b>Select the subject:</b>
                        <select name="subject">
                            <?php
                            require_once "includes/dbc.inc.php";
                            require_once "includes/coursesDbc.php";
                            $resultData = getAllSubjects($conn);
                            $nrRows = mysqli_num_rows($resultData);
                            for($i = 1; $i <= $nrRows; $i++) {
                                $row = mysqli_fetch_array($resultData);
                                if ($row["id"] == $_SESSION["edited_course"]["subject_id"]) {
                                    echo " <option value=" . $row["id"] . " selected>" . $row["name"] . "</option>";
                                }else {
                                    echo " <option value=" . $row["id"] . ">" . $row["name"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </label><br><br>

                    <label><b>Select the required skill level:</b>
                        <select name="skillLevel">
                            <?php
                            require_once "includes/dbc.inc.php";
                            require_once "includes/coursesDbc.php";
                            $resultData = getAllSkillLevels($conn);
                            $nrRows = mysqli_num_rows($resultData);
                            for($i = 1; $i <= $nrRows; $i++) {
                                $row = mysqli_fetch_array($resultData);
                                if ($row["id"] == $_SESSION["edited_course"]["skill_level_id"]) {
                                    echo " <option value=". $row["id"]. " selected>". $row["level"] ."</option>";
                                }else {
                                    echo " <option value=". $row["id"]. ">". $row["level"] ."</option>";
                                }
                            }
                            ?>
                        </select>
                    </label><br><br>

                <label for="duration"><b>Duration</b>
                    <input type="text" placeholder="<?php echo $_SESSION['edited_course']['duration']; ?>" name="duration">
                </label>

                <label for="description"><b>Description</b>
                    <textarea rows = "5" placeholder="<?php echo $_SESSION['edited_course']['description']; ?>" name="description"></textarea>
                </label>

                <label><b>Do you want to provide a certificate of completion?</b>
                        <select name="certificate">
                            <option value=0 <?php if($_SESSION["edited_course"]["certificate_of_completion"] == 0) {echo " selected";}?>>No</option>
                            <option value=1 <?php if($_SESSION["edited_course"]["certificate_of_completion"] == 1) {echo " selected";}?>>Yes</option>
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



