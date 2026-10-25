<?php
require_once("../controllers/AdminDashboardController.php");

$activePage = "dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MetroSheba</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body class="dashboard admin">

<?php include("admin_nav.php"); ?>

<div class="main-content">
    <div class="header">
        <h2>Dashboard Overview</h2>
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
            <h3 id="statCustomers"><?php echo count($customers); ?></h3>
            <p>Registered Customers</p>
        </div>
        <div class="card">
            <h3 id="statTickets"><?php echo (int)$stats['total_tickets']; ?></h3>
            <p>Total Sold Tickets</p>
        </div>
        <div class="card">
            <h3 id="statProfit"><?php echo number_format((int)$stats['total_profit']); ?> BDT</h3>
            <p>Total Profit</p>
        </div>
    </div>

    <div class="table-container">
        <div id="pageMessage" class="message-box" style="display:none;"></div>

        <!-- Shown by admin-users.js when an Edit button is clicked. -->
        <div class="table-card" id="editPanel" style="display:none; margin-bottom:20px;">
            <h3 style="margin-bottom:15px;">Update Customer</h3>

            <div id="editMessage" class="message-box" style="display:none;"></div>

            <form id="editForm" novalidate>
                <input type="hidden" id="edit_id" name="id">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name">
                        <span class="field-error" id="err_name"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                        <span class="field-error" id="err_email"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile_number">Mobile</label>
                        <input type="text" id="mobile_number" name="mobile_number" maxlength="11">
                        <span class="field-error" id="err_mobile_number"></span>
                    </div>

                    <div class="form-group">
                        <label for="nid">NID</label>
                        <input type="text" id="nid" name="nid">
                        <span class="field-error" id="err_nid"></span>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <span class="field-error" id="err_gender"></span>
                    </div>
                </div>

                <div class="row-actions">
                    <button type="submit" id="btnSaveUser" class="btn btn-primary">Save Changes</button>
                    <button type="button" id="btnCancelEdit" class="btn btn-muted">Cancel</button>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3>Customer Information
                    <small id="resultCount" style="color:#777; font-weight:400;"></small>
                </h3>

                <div class="table-tools">
                    <label class="visually-hidden" for="userSearch">Search customers</label>
                    <input type="text" id="userSearch" class="search-input"
                           placeholder="Search name, email, mobile or NID">
                </div>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>NID</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/validators.js"></script>
<script src="../assets/js/admin-users.js"></script>
</body>
</html>
