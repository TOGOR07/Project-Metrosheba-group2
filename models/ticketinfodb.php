<?php
require_once("db.php");

function getTicketInfoStats()
{
    global $conn;

    $stats = [
        "total_customers" => 0,
        "total_tickets" => 0,
        "total_revenue" => 0
    ];

    $q1 = "SELECT COUNT(id) as total_customers FROM users";
    $r1 = mysqli_query($conn, $q1);
    if ($r1) {
        $row = mysqli_fetch_assoc($r1);
        $stats["total_customers"] = (int)$row["total_customers"];
    }

    $q2 = "SELECT IFNULL(SUM(quantity),0) as total_tickets,
                  IFNULL(SUM(total_price),0) as total_revenue
           FROM metroshebatickets
           WHERE status = 'active'";
    $r2 = mysqli_query($conn, $q2);
    if ($r2) {
        $row = mysqli_fetch_assoc($r2);
        $stats["total_tickets"] = (int)$row["total_tickets"];
        $stats["total_revenue"] = (int)$row["total_revenue"];
    }

    return $stats;
}

// $search filters on buyer name or station. An empty string returns
// everything, which is what the page asks for on first load.
function getAllTickets($search = "")
{
    global $conn;

    $search = trim($search);

    $sql = "SELECT id, username, from_station, to_station, quantity,
                   per_price, total_price, purchase_date, status
            FROM metroshebatickets";

    if ($search !== "") {
        $sql .= " WHERE username LIKE ? OR from_station LIKE ? OR to_station LIKE ?";
    }

    $sql .= " ORDER BY id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare Failed (All Tickets): " . mysqli_error($conn));
    }

    if ($search !== "") {
        $like = "%" . $search . "%";
        mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tickets = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $tickets[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $tickets;
}

// Feeds the "busiest routes" chart on the admin ticket page.
function getTopRoutes($limit = 5)
{
    global $conn;

    $limit = intval($limit);

    $sql = "SELECT from_station, to_station,
                   SUM(quantity) AS sold,
                   SUM(total_price) AS revenue
            FROM metroshebatickets
            WHERE status = 'active'
            GROUP BY from_station, to_station
            ORDER BY sold DESC
            LIMIT ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare Failed (Top Routes): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $routes = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $routes[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $routes;
}
?>
