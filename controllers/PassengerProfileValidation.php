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

if (!empty($username)) {

    $data = getPassengerProfileByUsername($username);

    if ($data) {
        $name = !empty($data['name']) ? htmlspecialchars($data['name']) : "Not Set";
        $email = !empty($data['email']) ? htmlspecialchars($data['email']) : "Not Set";
        $mobile = !empty($data['mobilenum']) ? htmlspecialchars($data['mobilenum']) : "Not Set";
        $gender = !empty($data['gender']) ? htmlspecialchars($data['gender']) : "Not Set";
        $nid = !empty($data['nid']) ? htmlspecialchars($data['nid']) : "Not Set";
        $dob = !empty($data['dob']) ? htmlspecialchars($data['dob']) : "Not Set";

        if (!empty($data['profile_image'])) {
            $profile_image = htmlspecialchars($data['profile_image']);
        }
    }
}
?>
