<?php
// Page data for the passenger dashboard. The purchase itself now runs
// through controllers/purchase_ticket_api.php over AJAX, so this file
// only prepares what the first render needs.
require_once("../models/stations.php");
require_once("../models/passengerdashboardticketdb.php");

$username = $_SESSION["username"] ?? "";

if (empty($username)) {
    header("Location: login.php");
    exit();
}

$summary = getPassengerTicketSummary($username);
?>
