<?php

$fromStation = $toStation = $quantity = "";
$errors = [];
$successMessage = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fromStation = isset($_POST['from_station']) ? htmlspecialchars($_POST['from_station']) : '';
    $toStation = isset($_POST['to_station']) ? htmlspecialchars($_POST['to_station']) : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (empty($fromStation) || empty($toStation)) {
        $errors[] = "Please select both 'From' and 'To' stations.";
    }

    if ($fromStation === $toStation && !empty($fromStation)) {
        $errors[] = "Departure and Destination stations cannot be the same.";
    }

    if ($quantity < 1 || $quantity > 10) {
        $errors[] = "Ticket quantity must be between 1 and 10.";
    }

    if (empty($errors)) {

        $successMessage = "Success! You purchased $quantity ticket(s) from $fromStation to $toStation.";

        // $fromStation = $toStation = "";
        // $quantity = 1;
    }
}
?>
