<?php
require_once("../controllers/AdminDashboardAuthCheck.php");
require_once("../models/admindeshboarddb.php");

$stats = getTicketStats();
$customers = getAllUsers();
?>
