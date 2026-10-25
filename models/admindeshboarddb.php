<?php
require_once("db.php");

function getAllUsers()
{
    global $conn;
    $sql = "SELECT id, name, email, mobile_number, nid, gender FROM users ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $users = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}

function getTicketStats()
{
    global $conn;

    $stats = [
        "total_tickets" => 0,
        "total_profit" => 0
    ];

    // Cancelled tickets are refunded, so they count towards neither
    // the sold total nor the profit.
    $sql1 = "SELECT IFNULL(SUM(quantity),0) as total_tickets FROM metroshebatickets WHERE status = 'active'";
    $res1 = mysqli_query($conn, $sql1);
    if ($res1) {
        $row = mysqli_fetch_assoc($res1);
        $stats["total_tickets"] = $row["total_tickets"];
    }

    $sql2 = "SELECT IFNULL(SUM(total_price),0) as total_profit FROM metroshebatickets WHERE status = 'active'";
    $res2 = mysqli_query($conn, $sql2);
    if ($res2) {
        $row = mysqli_fetch_assoc($res2);
        $stats["total_profit"] = $row["total_profit"];
    }

    return $stats;
}

function getUserById($id)
{
    global $conn;

    $query = "SELECT id, name, email, mobile_number, nid, gender FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Get User): " . mysqli_error($conn));
    }

    $id = intval($id);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ? $row : null;
}

function updateUser($id, $name, $email, $mobile, $nid, $gender)
{
    global $conn;

    $query = "UPDATE users
              SET name = ?, email = ?, mobile_number = ?, nid = ?, gender = ?
              WHERE id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Update User): " . mysqli_error($conn));
    }

    $id = intval($id);
    mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $mobile, $nid, $gender, $id);

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

function deleteUser($id)
{
    global $conn;

    $query = "DELETE FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Delete User): " . mysqli_error($conn));
    }

    $id = intval($id);
    mysqli_stmt_bind_param($stmt, "i", $id);

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

// Used by the admin dashboard to tell an email clash apart from a real
// update failure. $exceptId keeps the row being edited out of the check.
function isEmailTakenByOther($email, $exceptId)
{
    global $conn;

    $query = "SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Email Check): " . mysqli_error($conn));
    }

    $exceptId = intval($exceptId);
    mysqli_stmt_bind_param($stmt, "si", $email, $exceptId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $taken = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    return $taken;
}
?>
