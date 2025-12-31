<?php
session_start();

$name = $email = $mobile = $dob = $gender = "";
$errors = [];

if (isset($_SESSION['user_data'])) {
    $data = $_SESSION['user_data'];
    $name = $data['name'] ?? "";
    $email = $data['email'] ?? "";
    $mobile = $data['mobile'] ?? "";
    $dob = $data['dob'] ?? "";
    $gender = $data['gender'] ?? "";
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

        $current_data = isset($_SESSION['user_data']) ? $_SESSION['user_data'] : [];

        $_SESSION['user_data'] = array_merge($current_data, [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'dob' => $dob,
            'gender' => $gender,
            'nid' => $current_data['nid'] ?? 'Not Set'
        ]);

        header("Location: PassengerProfile.php");
        exit();
    }
}
?>
