<?php
session_start();
require_once("../models/registrationdb.php");

$success_msg = "";
$errors = [];

if (!isset($_SESSION["otp_codes"])) {
    $_SESSION["otp_codes"] = array();
}

if (isset($_POST["ajax"]) && $_POST["ajax"] === "send_otp") {
    header("Content-Type: application/json; charset=UTF-8");

    $mobile = trim($_POST["mobile"] ?? "");

    if ($mobile === "") {
        echo json_encode(["success" => false, "message" => "Mobile number is required."]);
        exit;
    }

    if (!ctype_digit($mobile) || strlen($mobile) !== 11 || substr($mobile, 0, 2) !== "01") {
        echo json_encode(["success" => false, "message" => "Mobile must be 11 digits and start with 01."]);
        exit;
    }

    $otp = (string) random_int(100000, 999999);

    $_SESSION["otp_codes"][$mobile] = [
        "otp" => $otp,
        "time" => time()
    ];

    echo json_encode(["success" => true, "message" => "OTP sent. (Demo OTP: $otp)"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "register") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $nid = trim($_POST["nid"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $password = $_POST["password"] ?? "";
    $otp = trim($_POST["otp"] ?? "");

    if ($name === "") {
        $errors["name"] = "Name is required.";
    }

    if ($email === "") {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    } elseif (isEmailExists($email)) {
        $errors["email"] = "Email already registered.";
    }

    if ($mobile === "") {
        $errors["mobile"] = "Mobile number is required.";
    } elseif (!ctype_digit($mobile) || strlen($mobile) !== 11 || substr($mobile, 0, 2) !== "01") {
        $errors["mobile"] = "Mobile must be 11 digits and start with 01.";
    }

    if ($dob === "") {
        $errors["dob"] = "Date of Birth is required.";
    }

    if ($nid === "") {
        $errors["nid"] = "NID number is required.";
    } elseif (!ctype_digit($nid)) {
        $errors["nid"] = "NID must be numeric.";
    }

    if ($gender === "") {
        $errors["gender"] = "Gender is required.";
    }

    if ($password === "") {
        $errors["password"] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters.";
    }

    if ($otp === "") {
        $errors["otp"] = "OTP is required.";
    } elseif (!ctype_digit($otp) || strlen($otp) !== 6) {
        $errors["otp"] = "OTP must be 6 digits.";
    } elseif (!isset($_SESSION["otp_codes"][$mobile])) {
        $errors["otp"] = "Please click 'Send OTP' first.";
    } else {
        $savedOtp = $_SESSION["otp_codes"][$mobile]["otp"];
        if ($otp !== $savedOtp) {
            $errors["otp"] = "Invalid OTP.";
        }
    }

    if (empty($errors)) {

        $dob = $dob . " 00:00:00";

        $insert = registerUser($name, $email, $mobile, $dob, $nid, $gender, $password, $otp);

        if ($insert) {
            unset($_SESSION["otp_codes"][$mobile]);
            $success_msg = "Registration successful. You can login now.";
        } else {
            $errors["db"] = "Database error! Registration failed.";
        }
    }
}
?>
