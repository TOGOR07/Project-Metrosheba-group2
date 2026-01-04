<?php
session_start();
require_once("../models/passengerprofiledb.php");

$name = "Not Set";
$email = "Not Set";
$mobile = "Not Set";
$gender = "Not Set";
$nid = "Not Set";
$dob = "Not Set";

$profile_image = "../assets/user.png";

$username = $_SESSION['username'] ?? "";

if (empty($username)) {
    header("Location: Login.php");
    exit();
}

$data = getPassengerProfileByUsername($username);

if ($data) {
    $name = !empty($data['name']) ? htmlspecialchars($data['name']) : "Not Set";
    $email = !empty($data['email']) ? htmlspecialchars($data['email']) : "Not Set";
    $mobile = !empty($data['mobile_number']) ? htmlspecialchars($data['mobile_number']) : "Not Set";
    $gender = !empty($data['gender']) ? htmlspecialchars($data['gender']) : "Not Set";
    $nid = !empty($data['nid']) ? htmlspecialchars($data['nid']) : "Not Set";

    if (!empty($data['dob'])) {
        $dob = date("Y-m-d", strtotime($data['dob']));
    }
}
?>
