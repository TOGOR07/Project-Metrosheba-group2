<?php
require_once("../controllers/AdminDashboardAuthCheck.php");
require_once("../models/ticketinfodb.php");

$stats = getTicketInfoStats();
$tickets = getAllTickets();
?>
