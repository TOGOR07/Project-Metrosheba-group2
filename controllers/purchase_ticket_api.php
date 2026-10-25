<?php
require_once("api_bootstrap.php");
require_once("../models/stations.php");
require_once("../models/passengerdashboardticketdb.php");

requirePost();
$username = requireLogin("passenger");

$fromStation = trim($_POST["from_station"] ?? "");
$toStation   = trim($_POST["to_station"] ?? "");
$quantity    = (int)($_POST["quantity"] ?? 0);

$errors = [];

if ($fromStation === "" || $toStation === "") {
    $errors["station"] = "Please select both 'From' and 'To' stations.";
} else if (!isValidStation($fromStation) || !isValidStation($toStation)) {
    $errors["station"] = "Invalid station selected.";
} else if ($fromStation === $toStation) {
    $errors["station"] = "From and To station cannot be the same.";
}

if ($quantity < 1 || $quantity > MAX_TICKETS_PER_PURCHASE) {
    $errors["quantity"] = "Ticket quantity must be between 1 and " . MAX_TICKETS_PER_PURCHASE . ".";
}

if (!empty($errors)) {
    jsonReply(false, ["errors" => $errors], "Please correct the highlighted fields.");
}

// The price is recalculated here and the number posted by the browser is
// ignored, so a tampered form cannot buy a cheap ticket.
$perPrice = calculateFare($fromStation, $toStation);
$totalPrice = $perPrice * $quantity;

$ticketId = insertTicket($username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice);

if (!$ticketId) {
    jsonFail("Could not save your ticket. Please try again.", 500);
}

jsonReply(true, [
    "ticket" => [
        "id" => $ticketId,
        "username" => $username,
        "from_station" => $fromStation,
        "to_station" => $toStation,
        "quantity" => $quantity,
        "per_price" => $perPrice,
        "total_price" => $totalPrice,
        "purchase_date" => date("Y-m-d H:i:s")
    ],
    "summary" => getPassengerTicketSummary($username)
], "Ticket purchased successfully.");
?>
