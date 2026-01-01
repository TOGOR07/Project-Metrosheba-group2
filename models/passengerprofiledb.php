<?php
require_once("db.php");

function getPassengerProfileByUsername($username)
{
    global $conn;

    $query = "SELECT name, email, mobilenum, gender, nid, dob, profile_image 
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
?>
