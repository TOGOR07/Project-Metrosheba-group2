<?php
// Page data for the admin dashboard. Listing, editing and deleting users
// all run through controllers/admin_users_api.php over AJAX, so the
// destructive actions no longer sit behind a plain GET link.
require_once("AdminDashboardAuthCheck.php");
require_once("../models/admindeshboarddb.php");

$stats = getTicketStats();
$customers = getAllUsers();
?>
