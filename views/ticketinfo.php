<?php
require_once("../controllers/AdminDashboardAuthCheck.php");

$activePage = "tickets";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Sales | MetroSheba Admin</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body class="dashboard admin">

<?php include("admin_nav.php"); ?>

<div class="main-content">
    <div class="header">
        <h2>Ticket Sales Overview</h2>
        <div class="admin-info">
            <div style="text-align:right;">
                <strong>Admin</strong><br>
                <small style="color:gray;">Administrator</small>
            </div>
            <img src="../assets/user.png" class="admin-avatar" alt="Admin">
        </div>
    </div>

    <div class="stats-container">
        <div class="card">
            <h3 id="totalCustomers">0</h3>
            <p>Total Customers</p>
        </div>
        <div class="card">
            <h3 id="totalTickets">0</h3>
            <p>Total Sold Tickets</p>
        </div>
        <div class="card">
            <h3 id="totalRevenue">0 BDT</h3>
            <p>Total Revenue</p>
        </div>
    </div>

    <div class="table-container">
        <div class="table-card" style="margin-bottom:20px;">
            <h3>Busiest Routes</h3>
            <ul class="route-chart" id="routeChart"></ul>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3>Ticket Information
                    <small id="resultCount" style="color:#777; font-weight:400;"></small>
                </h3>

                <div class="table-tools">
                    <label class="visually-hidden" for="ticketSearch">Search ticket sales</label>
                    <input type="text" id="ticketSearch" class="search-input"
                           placeholder="Search passenger or station">
                    <button type="button" id="btnRefresh" class="btn btn-muted btn-small">Refresh</button>
                    <button type="button" id="btnExport" class="btn btn-primary btn-small">Export CSV</button>
                </div>
            </div>

            <div class="table-scroll">
                <table>
                    <thead id="ticketHead">
                        <tr>
                            <th class="sortable" data-column="id">ID</th>
                            <th class="sortable" data-column="username">Passenger</th>
                            <th class="sortable" data-column="from_station">From</th>
                            <th class="sortable" data-column="to_station">To</th>
                            <th class="sortable" data-column="quantity">Qty</th>
                            <th class="sortable" data-column="per_price">Per Price</th>
                            <th class="sortable" data-column="total_price">Total</th>
                            <th class="sortable" data-column="purchase_date">Purchase Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ticketBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/admin-tickets.js"></script>
</body>
</html>
