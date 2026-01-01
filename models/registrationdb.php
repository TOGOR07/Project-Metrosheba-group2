<?php
require_once("db.php");

function isEmailExists($email)
{
    global $conn;

    $query = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Email Check): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}


function registerUser($name, $email, $mobile, $dob, $nid, $gender, $password, $otp)
{
    global $conn;

    $hashedPass = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (name, email, mobile_number, dob, nid, gender, password, otp)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Register): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssissssi",
        $name,
        $email,
        $mobile,
        $dob,
        $nid,
        $gender,
        $hashedPass,
        $otp
    );

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
?>
