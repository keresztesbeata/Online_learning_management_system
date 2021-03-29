<?php

require_once "dbc.inc.php";
require_once "accountsDbc.php";
require_once "coursesDbc.php";

function printFoundCourse($conn,$course) {
        // print found course
        $teacher = getUser($conn, $course["teacher_id"]);
        $subject = getSubject($conn, $course["subject_id"]);
        if($teacher === false or $subject === false) {
            header("location:../courses.php?error=failedGettingCourses");
            exit();
        }
        $skillLevel = getSkillLevel($conn,$course["skill_level_id"]);
        printTableRow($course["name"],$subject,$skillLevel,$teacher,$course["course_id"]);
}

/* Print the list of courses */
function printCourses($conn,$resultData) {
        if ($resultData === null) {
            header("location:../courses.php?error=failedGettingCourses");
            exit();
        }
        $nrRows = mysqli_num_rows($resultData);
        for ($i = 1; $i <= $nrRows; $i++) {
            $row = mysqli_fetch_array($resultData);
            if ($row === null) {
                header("location:../courses.php?error=failedGettingCourses");
                exit();
            }
            $teacher = getUser($conn, $row["teacher_id"]);
            $subject = getSubject($conn, $row["subject_id"]);
            if ($teacher === false or $subject === false) {
                header("location:../courses.php?error=failedGettingCourses");
                exit();
            }
            $skillLevel = getSkillLevel($conn, $row["skill_level_id"]);
            printTableRow($row["name"], $subject, $skillLevel, $teacher, $row["course_id"]);
        }

}

/** Prints a row from the courses table with the given data */
function printTableRow($courseName,$subject,$skillLevel,$teacher,$courseId) {
    echo "
        <tr>
        <td >
        <h3>" . $courseName . "</h3></td>
        <td>"
        . $subject['name'] . "</td>
        <td>"
        . $skillLevel. "</td>
        <td>"
        . $teacher['first_name'] ." ". $teacher['last_name'] . "</td>
        <td>
        <a href='includes/viewCourse.inc.php?id=" . $courseId. "' style='color:black' >Visit Course</a>
        </td>
        </tr>
         ";
}

/** prints the subscribed courses of the given student */
function printSubscribedCourses($conn,$studentId) {
    // print table headers
    echo "<tr>
    <th style = 'text-align: center'><h4>Course name</th>
    <th style ='text-align: center'>Expiration date</th>
    <th style ='text-align: center'>Details</th>
    <th style ='text-align: center'>Unsubscribe</th>
    <th style ='text-align: center'>Remove Course</th><h4/></th>
    </tr>";

    // refresh the subscribed courses
    refreshSubscriptionStatus($conn,$_SESSION["student_id"]);
    $resultData = getSubscribedCourses($conn,$studentId);
    if($resultData === false) {
        header("location:../myCourses.php?error=noCourses");
        exit();
    }
    $nrRows = mysqli_num_rows($resultData);
    for($i = 1; $i <= $nrRows; $i++) {
        $row = mysqli_fetch_array($resultData);
        if ($row === null) {
            header("location:../courses.php?error=failedGettingCourses");
            exit();
        }else {
            printSubscribedCoursesRow($row["id"],$row["name"],$row["expDate"],$row["status"]);
        }
    }

}

/** check if subscription is expired */
function isExpiredSubscription($statusId) {
    return $statusId == 2;
}
/** Prints a row from the subscribed courses table with the given data */
function printSubscribedCoursesRow($courseId,$courseName,$expirationDate,$status) {
    if(isExpiredSubscription($status)) {
        echo "<tr style='color: #ff0000'>";
    }else {
        echo "<tr>";
    }
    echo "
        <td>
        " . $courseName. "</td>
        <td>"
        . $expirationDate . "</td>
        <td>
        <a href='includes/viewCourse.inc.php?id=" . $courseId. "' style='color:black' >View Course</a>
        </td>";
    if(!isExpiredSubscription($status)) {
        echo "
        <td>
         <form action='includes/myCourses.inc.php' method='post'>
            <button style = 'background: none;color:#9b2a38;text-decoration:none' type='submit' name='unsubscribe' value =" . $courseId . ">Unsubscribe</button>
        </form></td>";
    }else {
        echo "
        <td>
         Not available</td>";
    }
    echo "
        <td>
         <form action='includes/myCourses.inc.php' method='post'>
            <button style = 'background: none;color:#9b2a38;text-decoration:none' type='submit' name='remove' value =" . $courseId . ">Remove</button>
        </form></td>
        </tr>";
}

function printManagedCourses($conn,$teacherId) {
    // print table headers
    echo "<tr>
    <th style = 'text-align: center'><h4>Course name</th>
    <th style ='text-align: center'>Subject</th>
    <th style ='text-align: center'>Nr of subscribers</th>
    <th style ='text-align: center'>Edit Course</th>
    <th style ='text-align: center'>Delete Course</th><h4/></th>
    </tr>";
    $resultData = getCoursesOfTeacher($conn,$teacherId);
    if($resultData === null) {
        header("location:../myCourses.php?error=failedGettingCourses");
        exit();
    }
    $nrRows = mysqli_num_rows($resultData);
    for($i = 1; $i <= $nrRows; $i++) {
        $row = mysqli_fetch_array($resultData);
        if ($row === null) {
            header("location:../myCourses.php?error=failedGettingCourses");
            exit();
        }
        $subject = getSubject($conn, $row["subject_id"]);
        $noSubscribers = countSubscribersOfCourse($conn,$row["course_id"]);
        printManagedCoursesRow($row["name"],$subject,$noSubscribers,$row["course_id"]);
    }

}

/** Prints a row from the courses table with the given data */
function printManagedCoursesRow($courseName,$subject,$noSubscribers,$courseId) {
    echo "
        <tr>
        <td>
        " . $courseName . "
        </td>
        <td>"
        . $subject['name'] . "
        </td>
        <td>"
        . $noSubscribers . "
        </td>
        <td>
         <form action='includes/myCourses.inc.php' method='post'>
            <button style = 'background: none;color:#9b2a38;text-decoration:none' type='submit' name='edit' value =" . $courseId . ">Edit</button>
        </form></td>
        <td>
         <form action='includes/myCourses.inc.php' method='post'>
            <button style = 'background: none;color:#9b2a38;text-decoration:none' type='submit' name='delete' value =" . $courseId . ">Delete</button>
        </form></td>
        </tr>
         ";
}
/** delete a course */
 function deleteManagedCourse($conn,$courseId) {
     // delete all subscriptions to this course
     removeSubscriptions($conn,$courseId);
     // delete course
     deleteCourse($conn,$courseId);
 }
