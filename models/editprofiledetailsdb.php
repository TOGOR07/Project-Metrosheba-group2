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

        mysqli_stmt_bind_param($stmt, "ssssssss",
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

        mysqli_stmt_bind_param($stmt, "sssssss",
            $name, $email, $mobile, $dob, $nid, $gender, $oldName
        );
    }

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

// users.name is unique and tickets are keyed by it, so an edit that
// changes the name has to be checked and then carried over.
function isNameTakenByOther($name, $currentName)
{
    global $conn;

    $query = "SELECT id FROM users WHERE name = ? AND name <> ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Name Check): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $name, $currentName);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $taken = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    return $taken;
}

function isEmailTakenByOtherUser($email, $currentName)
{
    global $conn;

    $query = "SELECT id FROM users WHERE email = ? AND name <> ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Email Check): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $email, $currentName);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $taken = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    return $taken;
}

function renameTicketOwner($oldName, $newName)
{
    global $conn;

    $query = "UPDATE metroshebatickets SET username = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Rename Ticket Owner): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $newName, $oldName);

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
?>
