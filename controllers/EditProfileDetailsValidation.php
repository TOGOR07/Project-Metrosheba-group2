<?php
session_start();
require_once("../models/editprofiledetailsdb.php");

$name = $email = $mobile = $dob = $nid = $gender = "";
$errors = [];

$sessionName = $_SESSION['username'] ?? "";

if (empty($sessionName)) {
    header("Location: Login.php");
    exit();
}

$user = getUserBySessionName($sessionName);

if ($user) {
    $name = $user['name'];
    $email = $user['email'];
    $mobile = $user['mobile_number'];
    $nid = $user['nid'];
    $gender = $user['gender'];
    $dob = date("Y-m-d", strtotime($user['dob']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $newName = trim($_POST["name"] ?? "");
    $newEmail = trim($_POST["email"] ?? "");
    $newMobile = trim($_POST["mobile"] ?? "");
    $newDob = trim($_POST["dob"] ?? "");
    $newNid = trim($_POST["nid"] ?? "");
    $newGender = trim($_POST["gender"] ?? "");
    $newPassword = trim($_POST["password"] ?? "");

    if ($newName === "") $errors['name'] = "Name is required";

    if ($newEmail === "") $errors['email'] = "Email is required";
    else if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format";

    if ($newMobile === "") $errors['mobile'] = "Mobile number is required";
    else if (!ctype_digit($newMobile) || strlen($newMobile) !== 11) $errors['mobile'] = "Mobile must be 11 digits";

    if ($newDob === "") $errors['dob'] = "Date of Birth is required";

    if ($newNid === "") $errors['nid'] = "NID is required";
    else if (!ctype_digit($newNid)) $errors['nid'] = "NID must be numeric";

    if ($newGender === "") $errors['gender'] = "Gender is required";

    if (empty($errors)) {

        $newDob = $newDob . " 00:00:00";

        $passwordToUpdate = null;
        if ($newPassword !== "") {
            if (strlen($newPassword) < 8) {
                $errors['password'] = "Password must be at least 8 characters";
            } else {
                $passwordToUpdate = $newPassword;
            }
        }

        if (empty($errors)) {
            $update = updateUserDetails(
                $sessionName,
                $newName,
                $newEmail,
                $newMobile,
                $newDob,
                $newNid,
                $newGender,
                $passwordToUpdate
            );

            if ($update) {
                $_SESSION['username'] = $newName;
                header("Location: PassengerProfile.php");
                exit();
            } else {
                $errors['db'] = "Database update failed!";
            }
        }
    }

    $name = $newName;
    $email = $newEmail;
    $mobile = $newMobile;
    $dob = $newDob;
    $nid = $newNid;
    $gender = $newGender;
}
?>
