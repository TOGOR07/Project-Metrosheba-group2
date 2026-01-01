<?php
session_start();
require_once("../models/editprofiledetailsdb.php");

$name = $email = $mobile = $dob = $gender = "";
$errors = [];


$username = $_SESSION['username'] ?? "";

if (empty($username)) {
    header("Location: Login.php");
    exit();
}

$userData = getUserByUsername($username);

if ($userData) {
    $name = $userData['name'] ?? "";
    $email = $userData['email'] ?? "";
    $mobile = $userData['mobilenum'] ?? "";  
    $dob = $userData['dob'] ?? "";
    $gender = $userData['gender'] ?? "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $errors['name'] = "Name is required";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    if (empty($_POST["mobile"])) {
        $errors['mobile'] = "Mobile number is required";
    } elseif (!is_numeric($_POST["mobile"])) {
        $errors['mobile'] = "Mobile number must be numeric";
    } else {
        $mobile = htmlspecialchars($_POST["mobile"]);
    }

    if (empty($_POST["dob"])) {
        $errors['dob'] = "Date of Birth is required";
    } else {
        $dob = htmlspecialchars($_POST["dob"]);
    }

    if (empty($_POST["gender"])) {
        $errors['gender'] = "Gender is required";
    } else {
        $gender = htmlspecialchars($_POST["gender"]);
    }

    if (empty($errors)) {

        $update = updateUserProfileByUsername($username, $name, $email, $mobile, $dob, $gender);

        if ($update) {

            $_SESSION['username'] = $name;

            header("Location: PassengerProfile.php");
            exit();
        } else {
            $errors['db'] = "Database update failed!";
        }
    }
}
?>
