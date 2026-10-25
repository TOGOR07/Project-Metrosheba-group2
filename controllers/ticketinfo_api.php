<?php
require_once("api_bootstrap.php");
require_once("../models/ticketinfodb.php");

requireLogin("admin");

$search = trim($_GET["search"] ?? "");

jsonReply(true, [
    "stats" => getTicketInfoStats(),
    "tickets" => getAllTickets($search),
    "routes" => getTopRoutes(5)
]);
?>
