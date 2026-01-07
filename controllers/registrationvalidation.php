<?php
session_start();
require_once("../models/registrationdb.php");

$success_msg = "";
$errors = [];

if (!isset($_SESSION["otp_codes"])) {
    $_SESSION["otp_codes"] = array();
}

function hasDigitInText($text)
{
    $len = strlen($text);
    for ($i = 0; $i < $len; $i++) {
        if (ctype_digit($text[$i])) {
            return true;
        }
    }
    return false;
}

function getWordsPhp($text)
{
    $parts = explode(" ", $text);
    $words = array();

    for ($i = 0; $i < count($parts); $i++) {
        $w = trim($parts[$i]);
        if ($w !== "") {
            $words[] = $w;
        }
    }
    return $words;
}

function isSimpleEmailValid($email)
{
    if ($email === "") return false;
    if (strpos($email, " ") !== false) return false;

    $atPos = strpos($email, "@");
    if ($atPos === false) return false;
    if ($atPos == 0) return false;

    if (strpos($email, "@", $atPos + 1) !== false) return false;

    $dotPos = strrpos($email, ".");
    if ($dotPos === false) return false;
    if ($dotPos < $atPos + 2) return false;
    if ($dotPos == strlen($email) - 1) return false;

    return true;
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
    } else {
        if (hasDigitInText($name)) {
            $errors["name"] = "Name cannot contain numbers.";
        } else {
            $words = getWordsPhp($name);
            if (count($words) != 2) {
                $errors["name"] = "Name must be at least 2 words.";
            } else {
                if (strlen($words[0]) < 2 || strlen($words[1]) < 2) {
                    $errors["name"] = "Each word must be at least 2 characters.";
                }
            }
        }
    }

    if ($email === "") {
        $errors["email"] = "Email is required.";
    } else {
        if (!isSimpleEmailValid($email)) {
            $errors["email"] = "Invalid email format.";
        } else if (isEmailExists($email)) {
            $errors["email"] = "Email already registered.";
        }
    }

    if ($mobile === "") {
        $errors["mobile"] = "Mobile number is required.";
    } else if (!ctype_digit($mobile) || strlen($mobile) !== 11 || substr($mobile, 0, 2) !== "01") {
        $errors["mobile"] = "Mobile must be 11 digits and start with 01.";
    }

    if ($dob === "") {
        $errors["dob"] = "Date of Birth is required.";
    }

    if ($nid === "") {
        $errors["nid"] = "NID number is required.";
    } else if (!ctype_digit($nid)) {
        $errors["nid"] = "NID must be numeric.";
    }

    if ($gender === "") {
        $errors["gender"] = "Gender is required.";
    }

    if ($password === "") {
        $errors["password"] = "Password is required.";
    } else if (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters.";
    }

    if ($otp === "") {
        $errors["otp"] = "OTP is required.";
    } else if (!ctype_digit($otp) || strlen($otp) !== 6) {
        $errors["otp"] = "OTP must be 6 digits.";
    } else if (!isset($_SESSION["otp_codes"][$mobile])) {
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
