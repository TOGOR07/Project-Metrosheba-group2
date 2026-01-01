<?php
require_once("db.php");

class PassengerDashboardTicket {

    public function insertTicket($username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice){
        global $conn;

        $query = "INSERT INTO tickets(username, from_station, to_station, quantity, per_price, total_price, purchase_date)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssiii", $username, $fromStation, $toStation, $quantity, $perPrice, $totalPrice);

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }
}
?>
