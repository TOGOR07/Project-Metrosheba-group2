<?php
require_once("db.php");

function getUserBySessionName($sessionName)
{
    global $conn;

    $query = "SELECT * FROM users WHERE name = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Fetch User): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $sessionName);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return false;
}


function updateUserDetails($oldName, $name, $email, $mobile, $dob, $nid, $gender, $password = null)
{
    global $conn;

    if ($password !== null) {
       
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE users 
                  SET name=?, email=?, mobile_number=?, dob=?, nid=?, gender=?, password=? 
                  WHERE name=? LIMIT 1";

        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Prepare Failed (Update With Password): " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssisssss",
            $name, $email, $mobile, $dob, $nid, $gender, $hashedPass, $oldName
        );
    } else {
        $query = "UPDATE users 
                  SET name=?, email=?, mobile_number=?, dob=?, nid=?, gender=? 
                  WHERE name=? LIMIT 1";

        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Prepare Failed (Update Without Password): " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssissss",
            $name, $email, $mobile, $dob, $nid, $gender, $oldName
        );
    }

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
?>
