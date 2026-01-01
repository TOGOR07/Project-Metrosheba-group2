<?php
session_start();
require_once("../models/pessengerdashboardticket.php");

$fromStation = $toStation = $quantity = "";
$errors = [];
$successMessage = "";

$stations = [
    "Uttara North", "Uttara Center", "Uttara South", 
    "Pallabi", "Mirpur 11", "Mirpur 10", 
    "Kazipara", "Shewrapara", "Agargaon", 
    "Bijoy Sarani", "Farmgate", "Karwan Bazar", 
    "Shahbag", "Dhaka University", "Motijheel"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fromStation = isset($_POST['from_station']) ? htmlspecialchars($_POST['from_station']) : '';
    $toStation   = isset($_POST['to_station']) ? htmlspecialchars($_POST['to_station']) : '';
    $quantity    = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

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

        $fromIndex = array_search($fromStation, $stations);
        $toIndex   = array_search($toStation, $stations);

        $stationsPassed = abs($toIndex - $fromIndex);
        $perPrice = $stationsPassed * 10;
        if ($perPrice < 20) $perPrice = 20;

        $totalPrice = $perPrice * $quantity;

        $username = $_SESSION['username'] ?? "";

        if (empty($username)) {
            $errors[] = "User not found in session! Please login again.";
        } else {

            $ticketModel = new PassengerDashboardTicket();
            $insert = $ticketModel->insertTicket($username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice);

            if ($insert) {
                $successMessage = "Success! You purchased $quantity ticket(s) from $fromStation to $toStation.";
            } else {
                $errors[] = "Database Error! Ticket purchase failed.";
            }
        }
    }
}
?>
