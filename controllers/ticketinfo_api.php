<?php
header("Content-Type: application/json");

require_once("../models/ticketinfodb.php");

$stats = getTicketInfoStats();
$tickets = getAllTickets();

echo json_encode([
    "stats" => $stats,
    "tickets" => $tickets
]);
?>
