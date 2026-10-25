<?php
session_start();
require_once("../models/passengerprofiledb.php");

$name = $email = $mobile = $gender = $nid = $dob = "Not Set";
$profile_image = "../assets/user.png";

$username = $_SESSION['username'] ?? "";

if (empty($username)) {
    header("Location: login.php");
    exit();
}

$data = getPassengerProfileByUsername($username);

if ($data) {
    $name = !empty($data['name']) ? $data['name'] : "Not Set";
    $email = !empty($data['email']) ? $data['email'] : "Not Set";
    $mobile = !empty($data['mobile_number']) ? $data['mobile_number'] : "Not Set";
    $gender = !empty($data['gender']) ? $data['gender'] : "Not Set";
    $nid = !empty($data['nid']) ? $data['nid'] : "Not Set";

    if (!empty($data['dob'])) {
        $dob = date("d M Y", strtotime($data['dob']));
    }

    // Fall back to the placeholder if the row points at a file that was
    // removed from disk, so the avatar never renders as a broken image.
    if (!empty($data['profile_image'])) {
        $candidate = __DIR__ . "/../assets/uploads/" . $data['profile_image'];
        if (is_file($candidate)) {
            $profile_image = "../assets/uploads/" . $data['profile_image'];
        }
    }
}
?>
