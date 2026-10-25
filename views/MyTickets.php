<?php
require_once("../controllers/PassengerDashboardAuthCheck.php");
require_once("../models/passengerdashboardticketdb.php");

$username = $_SESSION["username"] ?? "";

if (empty($username)) {
    header("Location: login.php");
    exit();
}

// The table itself is filled over AJAX; these numbers give the page
// something meaningful to show on the very first paint.
$summary = getPassengerTicketSummary($username);
$activePage = "tickets";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets | MetroSheba</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body class="dashboard">

<?php include("passenger_nav.php"); ?>

<div class="main-content">
    <div class="header">
        <h2>My Tickets</h2>
        <div class="welcome-text">
            Welcome, <span><?php echo htmlspecialchars($username); ?></span>
        </div>
    </div>

    <div class="stats-container">
        <div class="card">
            <h3 id="statOrders"><?php echo (int)$summary['total_orders']; ?></h3>
            <p>Active Orders</p>
        </div>
        <div class="card">
            <h3 id="statTickets"><?php echo (int)$summary['total_tickets']; ?></h3>
            <p>Active Tickets</p>
        </div>
        <div class="card">
            <h3 id="statSpent"><?php echo number_format((int)$summary['total_spent']); ?> BDT</h3>
            <p>Total Spent</p>
        </div>
    </div>

    <div class="table-container">
        <div id="pageMessage" class="message-box" style="display:none;"></div>

        <div class="table-card">
            <div class="table-header">
                <h3>Purchase History <small id="resultCount" style="color:#777; font-weight:400;"></small></h3>

                <div class="table-tools">
                    <label class="visually-hidden" for="ticketSearch">Search tickets</label>
                    <input type="text" id="ticketSearch" class="search-input"
                           placeholder="Search by station or ticket no.">

                    <label class="visually-hidden" for="statusFilter">Filter by status</label>
                    <select id="statusFilter" style="width:auto;">
                        <option value="all">All statuses</option>
                        <option value="active">Active only</option>
                        <option value="cancelled">Cancelled only</option>
                    </select>
                </div>
            </div>

            <div class="table-scroll">
                <table>
                    <thead id="ticketHead">
                        <tr>
                            <th class="sortable" data-column="id">Ticket</th>
                            <th class="sortable" data-column="from_station">From</th>
                            <th class="sortable" data-column="to_station">To</th>
                            <th class="sortable" data-column="quantity">Qty</th>
                            <th class="sortable" data-column="per_price">Per Price</th>
                            <th class="sortable" data-column="total_price">Total</th>
                            <th class="sortable" data-column="purchase_date">Purchased</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ticketBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/mytickets.js"></script>
</body>
</html>
