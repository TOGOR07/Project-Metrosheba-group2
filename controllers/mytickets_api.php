<?php
require_once("api_bootstrap.php");
require_once("../models/passengerdashboardticketdb.php");

$username = requireLogin("passenger");

$action = $_POST["action"] ?? $_GET["action"] ?? "list";

if ($action === "cancel") {
    requirePost();

    $ticketId = (int)($_POST["ticket_id"] ?? 0);

    if ($ticketId < 1) {
        jsonFail("Invalid ticket selected.");
    }

    // cancelTicket() scopes the update to this passenger, so a guessed id
    // belonging to somebody else simply affects no rows.
    if (!cancelTicket($ticketId, $username)) {
        jsonFail("That ticket could not be cancelled. It may already be cancelled.");
    }

    jsonReply(true, [
        "ticket_id" => $ticketId,
        "summary" => getPassengerTicketSummary($username)
    ], "Ticket cancelled.");
}

jsonReply(true, [
    "tickets" => getTicketsByUsername($username),
    "summary" => getPassengerTicketSummary($username)
]);
?>
