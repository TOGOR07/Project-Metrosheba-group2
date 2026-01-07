<?php
require_once("../controllers/AdminDashboardAuthCheck.php");
require_once("../models/admindeshboarddb.php");

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    deleteUser($deleteId);
    header("../views/AdminDashboard.php");
    exit();
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile_number'];
    $nid = $_POST['nid'];
    $gender = $_POST['gender'];

    updateUser($id, $name, $email, $mobile, $nid, $gender);

    header("../views/AdminDashboard.php");
    exit();
}

$stats = getTicketStats();
$customers = getAllUsers();
?>
