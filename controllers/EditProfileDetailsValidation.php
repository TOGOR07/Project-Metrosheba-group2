<?php
session_start();
require_once("../models/editprofiledetailsdb.php");
require_once("../models/passengerprofiledb.php");

$name = $email = $mobile = $dob = $nid = $gender = "";
$errors = [];
$profile_image = "../assets/user.png";

$sessionName = $_SESSION['username'] ?? "";

if (empty($sessionName)) {
    header("Location: login.php");
    exit();
}

$user = getUserBySessionName($sessionName);

if ($user) {
    $name = $user['name'];
    $email = $user['email'];
    $mobile = $user['mobile_number'];
    $nid = $user['nid'];
    $gender = $user['gender'];
    $dob = !empty($user['dob']) ? date("Y-m-d", strtotime($user['dob'])) : "";

    if (!empty($user['profile_image'])) {
        $candidate = __DIR__ . "/../assets/uploads/" . $user['profile_image'];
        if (is_file($candidate)) {
            $profile_image = "../assets/uploads/" . $user['profile_image'];
        }
    }
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
    else if (isNameTakenByOther($newName, $sessionName)) $errors['name'] = "That name is already in use";

    if ($newEmail === "") $errors['email'] = "Email is required";
    else if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format";
    else if (isEmailTakenByOtherUser($newEmail, $sessionName)) $errors['email'] = "That email is already registered";

    if ($newMobile === "") $errors['mobile'] = "Mobile number is required";
    else if (!ctype_digit($newMobile) || strlen($newMobile) !== 11 || substr($newMobile, 0, 2) !== "01") {
        $errors['mobile'] = "Mobile must be 11 digits and start with 01";
    }

    if ($newDob === "") $errors['dob'] = "Date of Birth is required";

    if ($newNid === "") $errors['nid'] = "NID is required";
    else if (!ctype_digit($newNid)) $errors['nid'] = "NID must be numeric";

    if (!in_array($newGender, ["Male", "Female", "Other"], true)) $errors['gender'] = "Gender is required";

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
            $newDob . " 00:00:00",
            $newNid,
            $newGender,
            $passwordToUpdate
        );

        if ($update) {
            // Tickets are keyed by the buyer's name, so a rename has to
            // follow through to the ticket rows or the passenger's
            // history would disappear from their account.
            if ($newName !== $sessionName) {
                renameTicketOwner($sessionName, $newName);
            }

            $_SESSION['username'] = $newName;
            header("Location: PassengerProfile.php");
            exit();
        }

        $errors['db'] = "Database update failed!";
    }

    $name = $newName;
    $email = $newEmail;
    $mobile = $newMobile;
    $dob = $newDob;
    $nid = $newNid;
    $gender = $newGender;
}
?>
