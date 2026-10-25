<?php
require_once("api_bootstrap.php");
require_once("../models/admindeshboarddb.php");

requireLogin("admin");

$action = $_POST["action"] ?? $_GET["action"] ?? "list";

if ($action === "delete") {
    requirePost();

    $id = (int)($_POST["id"] ?? 0);

    if ($id < 1) {
        jsonFail("Invalid user selected.");
    }

    if (!getUserById($id)) {
        jsonFail("That user no longer exists.", 404);
    }

    if (!deleteUser($id)) {
        jsonFail("Could not delete the user.", 500);
    }

    jsonReply(true, ["id" => $id, "stats" => getTicketStats()], "User deleted.");
}

if ($action === "update") {
    requirePost();

    $id     = (int)($_POST["id"] ?? 0);
    $name   = trim($_POST["name"] ?? "");
    $email  = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile_number"] ?? "");
    $nid    = trim($_POST["nid"] ?? "");
    $gender = trim($_POST["gender"] ?? "");

    $errors = [];

    if ($id < 1) {
        jsonFail("Invalid user selected.");
    }

    if ($name === "") {
        $errors["name"] = "Name is required.";
    }

    if ($email === "") {
        $errors["email"] = "Email is required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    } else if (isEmailTakenByOther($email, $id)) {
        $errors["email"] = "Another account already uses this email.";
    }

    if ($mobile === "") {
        $errors["mobile_number"] = "Mobile number is required.";
    } else if (!ctype_digit($mobile) || strlen($mobile) !== 11 || substr($mobile, 0, 2) !== "01") {
        $errors["mobile_number"] = "Mobile must be 11 digits and start with 01.";
    }

    if ($nid === "") {
        $errors["nid"] = "NID is required.";
    } else if (!ctype_digit($nid)) {
        $errors["nid"] = "NID must be numeric.";
    }

    if (!in_array($gender, ["Male", "Female", "Other"], true)) {
        $errors["gender"] = "Please select a gender.";
    }

    if (!empty($errors)) {
        jsonReply(false, ["errors" => $errors], "Please correct the highlighted fields.");
    }

    if (!updateUser($id, $name, $email, $mobile, $nid, $gender)) {
        jsonFail("Could not update the user.", 500);
    }

    jsonReply(true, ["user" => getUserById($id)], "User updated.");
}

jsonReply(true, [
    "users" => getAllUsers(),
    "stats" => getTicketStats()
]);
?>
