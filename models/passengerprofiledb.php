<?php
require_once("db.php");

function getPassengerProfileByUsername($username)
{
    global $conn;

    $query = "SELECT name, email, mobile_number, gender, nid, dob, profile_image
              FROM users
              WHERE name = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (PassengerProfile): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ? $row : false;
}

// $fileName is the bare file name, e.g. "profile_1767264677.png".
// Views build the full path, so moving the uploads folder stays a
// one-line change instead of a data migration.
function updateProfileImage($username, $fileName)
{
    global $conn;

    $query = "UPDATE users SET profile_image = ? WHERE name = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Profile Image): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $fileName, $username);

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
?>
