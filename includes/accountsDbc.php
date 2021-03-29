<?php

require_once 'dbc.inc.php';

/*
 * Select
 */

/**
 *  Check if there exists a user with given username or email
 * @param $conn = connection to the db
 * @param $username = username of the user
 * @param $email = email of the user
 * @return false|string[]|null = id of the user was found
 */
function userExists($conn,$username,$email) {
    $sql = "SELECT * FROM user WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../login.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss",$username,$email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

/** Get user by id */
function getUser($conn,$userid) {
    $sql = "SELECT * FROM user WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../login.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$userid);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

/** Get student by id */
function getStudent($conn,$studentid) {
    $sql = "SELECT * FROM student WHERE student_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$studentid);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

/** Get teacher by id */
function getTeacher($conn,$teacherid) {
    $sql = "SELECT * FROM teacher WHERE teacher_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$teacherid);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}


/*
 * Insert
 */

/**
 * Create a new user with the given data.
 */
function createUser($conn,$email,$username,$password,$firstname,$lastname) {
    $sql = "INSERT INTO user (first_name, last_name, email,username,user_password) VALUES ('$firstname', '$lastname', '$email','$username','$password')";
    if(mysqli_query($conn, $sql)){
        return true;
    } else{
        return false;
    }
}

/**
 * Creates a student record with the given id.
 * @param $conn = connection to db
 * @param $userid = id of the user
 */
function createStudent($conn,$userid) {
    $sql = "INSERT INTO student (student_id) values(?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../login.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
/**
 * Creates a teacher record with the given id.
 * @param $conn = connection to db
 * @param $userid = id of the user
 */
function createTeacher($conn,$userid) {
    $sql = "INSERT INTO teacher (teacher_id) values(?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../login.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"i",$userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


/*
 * Update
 */

/** set the new data to the corresponding user */
function updateUser($conn,$email,$username,$password,$firstname,$lastname) {
    $sql = "UPDATE user SET first_name = ?, last_name = ?, email = ?, username = ?, user_password = ? WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"sssssi",$firstname,$lastname,$email,$username,$password,$_SESSION["user_id"]);
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}

/** set the new values for the currently logged in student*/
function updateStudent($conn,$faculty,$graduateTypeId) {

    $stmt = mysqli_stmt_init($conn);
    if($faculty !== null) {
        $sql = "UPDATE student SET faculty = ? where student_id = ?";
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../myAccount.php?error=failedToPrepStmt");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $faculty, $_SESSION["student_id"]);
        if(mysqli_stmt_execute($stmt) === false) {
            return false;
        }
    }
    if($graduateTypeId !== null) {
        $sql = "UPDATE student SET graduate_type_id = ? where student_id = ?";
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../myAccount.php?error=failedToPrepStmt");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $graduateTypeId, $_SESSION["student_id"]);
        if(mysqli_stmt_execute($stmt) === false) {
            return false;
        }
    }
    mysqli_stmt_close($stmt);
    return true;
}


/** set the new values for the currently logged in student*/
function updateTeacher($conn,$specialty) {
    $stmt = mysqli_stmt_init($conn);
    if($specialty!== null) {
        $sql = "UPDATE teacher SET specialty = ? where teacher_id = ?";
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../myAccount.php?error=failedToPrepStmt");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $specialty, $_SESSION["teacher_id"]);
        if(mysqli_stmt_execute($stmt) === false) {
            return false;
        }
    }
    mysqli_stmt_close($stmt);
    return  true;
}

/*
 * Delete
 */

/** delete the currently logged in student */
function deleteStudent($conn) {
    $sql = "DELETE FROM student where student_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["student_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
/** delete the currently logged in teacher */
function deleteTeacher($conn) {
    $sql = "DELETE FROM teacher where teacher_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["teacher_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/** delete the currently logged in user */
function deleteUser($conn) {
    $sql = "DELETE FROM user where user_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../myAccount.php?error=failedToPrepStmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
function getAllGraduateTypes($conn) {
    $sql = "SELECT * FROM graduate_type ORDER BY id;";
    return mysqli_query($conn, $sql);
}

