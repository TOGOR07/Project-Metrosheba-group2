<?php
require_once("db.php");

function getUserByUsername($username)
{
    global $conn;

    $query = "SELECT * FROM users WHERE name = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Login): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return false;
}
?>
