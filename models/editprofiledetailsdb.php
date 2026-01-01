<?php
require_once("db.php");
function getUserByUsername($username)
{
    global $conn;

    $query = "SELECT name, email, mobilenum, dob, gender 
              FROM users 
              WHERE name = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return false;
}


function updateUserProfileByUsername($username, $name, $email, $mobile, $dob, $gender)
{
    global $conn;

    $query = "UPDATE users 
              SET name = ?, email = ?, mobilenum = ?, dob = ?, gender = ?
              WHERE name = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss",
        $name,
        $email,
        $mobile,
        $dob,
        $gender,
        $username
    );

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
?>
