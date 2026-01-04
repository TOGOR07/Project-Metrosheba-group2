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

    $sql1 = "SELECT IFNULL(SUM(quantity),0) as total_tickets FROM metroshebatickets";
    $res1 = mysqli_query($conn, $sql1);
    if ($res1) {
        $row = mysqli_fetch_assoc($res1);
        $stats["total_tickets"] = $row["total_tickets"];
    }

    $sql2 = "SELECT IFNULL(SUM(total_price),0) as total_profit FROM metroshebatickets";
    $res2 = mysqli_query($conn, $sql2);
    if ($res2) {
        $row = mysqli_fetch_assoc($res2);
        $stats["total_profit"] = $row["total_profit"];
    }

    return $stats;
}
?>
