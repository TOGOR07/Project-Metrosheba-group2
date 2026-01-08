<?php
session_start();

require_once("../models/db.php");
require_once("../models/config.php");

$error = "";

function createRememberCookie($email)
{
    $expire = time() + (86400 * REMEMBER_DAYS);
    $data = $email . "|" . $expire;
    $signature = hash_hmac("sha256", $data, APP_SECRET_KEY);
    $cookieValue = $data . "|" . $signature;
    setcookie("remember_me", $cookieValue, $expire, "/");
}

function clearRememberCookie()
{
    if (isset($_COOKIE["remember_me"])) {
        setcookie("remember_me", "", time() - 3600, "/");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "login") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Email and Password required!";
    } else {

        // Admin (hard-coded)
        // change password here if you use different one (admin / admin1)
        if ($email === "admin@gmail.com" && $password === "admin") {
            $_SESSION["username"] = "admin";
            $_SESSION["email"] = "admin";
            $_SESSION["role"] = "admin";

            if (isset($_POST["remember"])) createRememberCookie("admin");
            else clearRememberCookie();

            header("Location: ../views/AdminDashboard.php");
            exit();
        }

        // Passenger: login by email
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {

                if (password_verify($password, $user["password"])) {

                    // IMPORTANT: store both
                    $_SESSION["username"] = $user["name"];   // display name
                    $_SESSION["email"] = $user["email"];     // login identity
                    $_SESSION["role"] = "passenger";

                    if (isset($_POST["remember"])) createRememberCookie($user["email"]);
                    else clearRememberCookie();

                    header("Location: ../views/PassengerDashboard.php");
                    exit();

                } else {
                    $error = "Wrong password!";
                }

            } else {
                $error = "User not found!";
            }

        } else {
            $error = "Query Failed!";
        }
    }
}
?>
