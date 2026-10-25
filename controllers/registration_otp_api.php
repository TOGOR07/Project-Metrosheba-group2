<?php
require_once("api_bootstrap.php");

requirePost();

if (!isset($_SESSION["otp_codes"])) {
    $_SESSION["otp_codes"] = [];
}

$mobile = trim($_POST["mobile"] ?? "");

if ($mobile === "") {
    jsonFail("Mobile number is required.");
}

if (!ctype_digit($mobile) || strlen($mobile) !== 11 || substr($mobile, 0, 2) !== "01") {
    jsonFail("Mobile must be 11 digits and start with 01.");
}

$otp = (string) random_int(100000, 999999);

$_SESSION["otp_codes"][$mobile] = [
    "otp" => $otp,
    "time" => time()
];

// A real deployment would hand this to an SMS gateway. For the
// prototype the code is shown on screen so registration can be tested.
jsonReply(true, ["demo_otp" => $otp], "OTP sent. (Demo OTP: " . $otp . ")");
?>
