<?php
require_once("db.php");

function insertTicket($username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice)
{
    global $conn;

    $query = "INSERT INTO metroshebatickets
              (username, from_station, to_station, quantity, per_price, total_price, purchase_date)
              VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Insert Ticket): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sssiii",
        $username,
        $fromStation,
        $toStation,
        $quantity,
        $perPrice,
        $totalPrice
    );

    $result = mysqli_stmt_execute($stmt);
    $newId = $result ? mysqli_insert_id($conn) : 0;
    mysqli_stmt_close($stmt);

    // The AJAX purchase needs the new id so it can render the receipt.
    return $result ? $newId : false;
}

function getTicketsByUsername($username)
{
    global $conn;

    $query = "SELECT id, from_station, to_station, quantity, per_price, total_price, purchase_date, status
              FROM metroshebatickets
              WHERE username = ?
              ORDER BY id DESC";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (My Tickets): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tickets = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $tickets[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $tickets;
}

function getPassengerTicketSummary($username)
{
    global $conn;

    $summary = [
        "total_orders" => 0,
        "total_tickets" => 0,
        "total_spent" => 0
    ];

    $query = "SELECT COUNT(id) AS total_orders,
                     IFNULL(SUM(quantity),0) AS total_tickets,
                     IFNULL(SUM(total_price),0) AS total_spent
              FROM metroshebatickets
              WHERE username = ? AND status = 'active'";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Ticket Summary): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $summary["total_orders"] = (int)$row["total_orders"];
        $summary["total_tickets"] = (int)$row["total_tickets"];
        $summary["total_spent"] = (int)$row["total_spent"];
    }

    mysqli_stmt_close($stmt);

    return $summary;
}

// A passenger may only cancel their own ticket, so the username is part
// of the WHERE clause rather than trusting the id alone.
function cancelTicket($ticketId, $username)
{
    global $conn;

    $query = "UPDATE metroshebatickets
              SET status = 'cancelled'
              WHERE id = ? AND username = ? AND status = 'active'
              LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Prepare Failed (Cancel Ticket): " . mysqli_error($conn));
    }

    $ticketId = intval($ticketId);
    mysqli_stmt_bind_param($stmt, "is", $ticketId, $username);
    mysqli_stmt_execute($stmt);

    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    return $affected > 0;
}
?>
