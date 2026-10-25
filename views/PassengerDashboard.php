<?php
require_once("../controllers/PassengerDashboardAuthCheck.php");
require_once("../controllers/PassengerDashboardStationCheck.php");

$activePage = "dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Ticket | MetroSheba</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body class="dashboard">

<?php include("passenger_nav.php"); ?>

<div class="main-content">
    <div class="header">
        <h2>Passenger Dashboard</h2>
        <div class="welcome-text">
            Welcome, <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="stats-container">
        <div class="card">
            <h3 id="statOrders"><?php echo (int)$summary['total_orders']; ?></h3>
            <p>Your Orders</p>
        </div>
        <div class="card">
            <h3 id="statTickets"><?php echo (int)$summary['total_tickets']; ?></h3>
            <p>Tickets Bought</p>
        </div>
        <div class="card">
            <h3 id="statSpent"><?php echo number_format((int)$summary['total_spent']); ?> BDT</h3>
            <p>Total Spent</p>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="booking-card">
            <h3>Purchase Ticket</h3>

            <div id="formMessage" class="message-box" style="display:none;"></div>

            <form id="ticketForm" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fromStation">From Station</label>
                        <select id="fromStation" name="from_station">
                            <option value="">Select Station</option>
                        </select>
                        <span class="field-error" id="err_fromStation"></span>
                    </div>

                    <div class="form-group">
                        <label for="toStation">To Station</label>
                        <select id="toStation" name="to_station">
                            <option value="">Select Station</option>
                        </select>
                        <span class="field-error" id="err_toStation"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantity">Total Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                    <span class="field-error" id="err_quantity"></span>
                </div>

                <div class="price-info">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="perPrice">Per Ticket Price</label>
                            <input type="text" id="perPrice" value="0 BDT" readonly>
                        </div>
                        <div class="form-group">
                            <label for="totalPrice">Total Price</label>
                            <input type="text" id="totalPrice" value="0 BDT" readonly>
                        </div>
                    </div>
                </div>

                <p class="notice">* The fare increases by 10 BDT for each station, minimum 20 BDT.</p>

                <button type="submit" id="btnConfirm" class="btn btn-primary btn-block">Confirm Purchase</button>
            </form>

            <div id="receiptArea"></div>
        </div>

        <div class="map-card">
            <h4>Route Map</h4>
            <ul class="station-list" id="mapList"></ul>
        </div>
    </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/validators.js"></script>
<script src="../assets/js/passenger-dashboard.js"></script>
</body>
</html>
