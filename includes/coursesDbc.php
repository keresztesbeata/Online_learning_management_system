<?php

require_once "dbc.inc.php";

/*
 * Get course by its id.
 */
function getCourse($conn,$courseId) {
    $sql = "SELECT * FROM course WHERE course_id=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$courseId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}
/** Get course by its (unique) name. */
function getCourseByName($conn,$name) {
    $sql = "SELECT * FROM course WHERE name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}


function getSubjectByName($conn,$subject) {
    $sql = "SELECT id FROM subject where name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$subject);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

/* Get all courses */
function getAllCourses($conn) {
    $sql = "SELECT * FROM course";
    return mysqli_query($conn, $sql);
}
/* Get all courses from a given subject */
function getCoursesOfSubject($conn,$subjectId) {
    // first get the subject's id
    $sql = "SELECT * FROM course where subject_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt,$sql) === false) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$subjectId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/* get skill level by id */
function getSkillLevel($conn,$levelId) {
    $sql = "SELECT level FROM skill_level where id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$levelId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultData)["level"];
}
/* get subject by id */
function getSubject($conn,$subjectId) {
    $sql = "SELECT DISTINCT name FROM subject where id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$subjectId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}


/** refresh the status of the subscription */
function refreshSubscriptionStatus($conn,$studentId) {
    $sql = "UPDATE subscription SET status_id = 2 WHERE student_id = ? and expiration_date < current_date;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$studentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
/** add a subscription */
function addSubscription($conn,$courseId,$studentId) {
    $period = getCourse($conn,$courseId)["duration"];
    $sql = "INSERT INTO subscription(course_id,student_id,expiration_date) values(?,?,date_add(curdate(),interval ? month));";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../viewCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"iii",$courseId,$studentId,$period);
    if(mysqli_stmt_execute($stmt)) {
        return true;
    }
    else return false;
}
/** function to renew the subscription */
function renewSubscription($conn,$courseId,$studentId) {
    $period = getCourse($conn,$courseId)["duration"];
    $sql = "UPDATE subscription SET status_id = 1, expiration_date = date_add(curdate(),interval ? month) where course_id = ? and student_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../viewCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"iii",$period,$courseId,$studentId);
    if(mysqli_stmt_execute($stmt)) {
        return true;
    }
    else return false;
}

/* Get subscription */
function getSubscription($conn,$courseId,$studentId) {
    $sql = "SELECT * FROM subscription where course_id = ? and student_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../viewCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ii",$courseId,$studentId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    if($row === null) {
        return false;
    }else {
        return $row;
    }
}
/** get all the subscribed courses of a student */
function getSubscribedCourses($conn,$studentId) {
    $sql = "SELECT c.course_id as id,c.name as name,s.expiration_date as expDate,s.status_id as status FROM course as c INNER JOIN subscription as s on c.course_id = s.course_id where s.student_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$studentId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
/** unsubscribe from a course */
function unSubscribe($conn,$courseId,$studentId) {
    $sql = "UPDATE subscription SET status_id = 2 WHERE course_id = ? and student_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ii",$courseId,$studentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/** delete a course from the subscribed ones */
function removeSubscribedCourse($conn,$courseId,$studentId) {
    $sql = "DELETE FROM subscription WHERE course_id = ? and student_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ii",$courseId,$studentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/** get courses managed by a teacher */
function getCoursesOfTeacher($conn,$teacherId) {
    $sql = "SELECT * FROM course where teacher_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$teacherId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/** count the nr of students subscribed to a course */
function countSubscribersOfCourse($conn,$courseId) {
    $sql = "SELECT COUNT(*) as nrSubscribers FROM subscription WHERE course_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myCourses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$courseId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    if($row !== null) {
        return $row["nrSubscribers"];
    }else {
        return 0;
    }
}
/** delete the subscriptions to the given course */
function removeSubscriptions($conn,$courseId) {
    $sql = "DELETE FROM subscription WHERE course_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$courseId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
/** delete a course */
function deleteCourse($conn,$courseId) {
    $sql = "DELETE FROM course WHERE course_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$courseId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
/** get all subject names */
function getAllSubjects($conn) {
    $sql = "SELECT * FROM subject;";
    return mysqli_query($conn, $sql);
}
/** get all skill level names */
function getAllSkillLevels($conn) {
    $sql = "SELECT * FROM skill_level ORDER BY id;";
    return mysqli_query($conn, $sql);
}
/** create a new course */
function createCourse($conn,$name,$subjectId,$teacherId,$skillLevelId,$duration,$description,$certificate) {
    $sql = "INSERT INTO course(name,subject_id,teacher_id,duration,description,certificate_of_completion,skill_level_id) VALUES(?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"siiisii",$name,$subjectId,$teacherId,$duration,$description,$certificate,$skillLevelId);
    return mysqli_stmt_execute($stmt);
}
/** create a new course */
function updateCourse($conn,$name,$subjectId,$teacherId,$skillLevelId,$duration,$description,$certificate,$courseId) {
    $sql = "UPDATE course SET name = ?,subject_id=?,teacher_id=?,duration=?,description=?,certificate_of_completion=?,skill_level_id=? where course_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../courses.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"siiisiii",$name,$subjectId,$teacherId,$duration,$description,$certificate,$skillLevelId,$courseId);
    return mysqli_stmt_execute($stmt);
}