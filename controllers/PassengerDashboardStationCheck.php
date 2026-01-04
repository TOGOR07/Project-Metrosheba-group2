<?php
require_once("../models/passengerdashboardticketdb.php");

$fromStation = $toStation = "";
$quantity = 1;
$errors = [];
$successMessage = "";
$totalPrice = 0;
$perPrice = 0;

$username = $_SESSION["username"] ?? "";

if (empty($username)) {
    header("Location: Login.php");
    exit();
}

$stations = [
    "Uttara North", "Uttara Center", "Uttara South",
    "Pallabi", "Mirpur 11", "Mirpur 10",
    "Kazipara", "Shewrapara", "Agargaon",
    "Bijoy Sarani", "Farmgate", "Karwan Bazar",
    "Shahbag", "Dhaka University", "Motijheel"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fromStation = trim($_POST['from_station'] ?? "");
    $toStation = trim($_POST['to_station'] ?? "");
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (empty($fromStation) || empty($toStation)) {
        $errors[] = "Please select both 'From' and 'To' stations.";
    }

    if ($fromStation === $toStation) {
        $errors[] = "From and To station cannot be same.";
    }

    if ($quantity < 1 || $quantity > 10) {
        $errors[] = "Ticket quantity must be between 1 and 10.";
    }

    if (empty($errors)) {

        $fromIndex = array_search($fromStation, $stations);
        $toIndex = array_search($toStation, $stations);

        if ($fromIndex === false || $toIndex === false) {
            $errors[] = "Invalid station selected!";
        } else {

            $stationsPassed = abs($toIndex - $fromIndex);
            $perPrice = $stationsPassed * 10;
            if ($perPrice < 20) $perPrice = 20;

            $totalPrice = $perPrice * $quantity;

            $insert = insertTicket($username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice);

            if ($insert) {
                $successMessage = "Success! Ticket purchased successfully.";
                $fromStation = $toStation = "";
                $quantity = 1;
                $totalPrice = 0;
                $perPrice = 0;
            } else {
                $errors[] = "Database Insert Failed!";
            }
        }
    }
}
?>
