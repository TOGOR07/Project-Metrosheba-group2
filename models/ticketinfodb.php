<?php
require_once("db.php");

function getTicketInfoStats()
{
    global $conn;

    $stats = [
        "total_customers" => 0,
        "total_tickets" => 0
    ];

    $q1 = "SELECT COUNT(id) as total_customers FROM users";
    $r1 = mysqli_query($conn, $q1);
    if ($r1) {
        $row = mysqli_fetch_assoc($r1);
        $stats["total_customers"] = $row["total_customers"];
    }

    $q2 = "SELECT IFNULL(SUM(quantity),0) as total_tickets FROM metroshebatickets";
    $r2 = mysqli_query($conn, $q2);
    if ($r2) {
        $row = mysqli_fetch_assoc($r2);
        $stats["total_tickets"] = $row["total_tickets"];
    }

    return $stats;
}


function getAllTickets()
{
    global $conn;
    $sql = "SELECT id, username, from_station, to_station, quantity, per_price, total_price, purchase_date 
            FROM metroshebatickets 
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    $tickets = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tickets[] = $row;
        }
    }

    return $tickets;
}
?>
