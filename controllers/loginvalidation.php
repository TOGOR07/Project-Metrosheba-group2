<?php
session_start();

require_once("../models/db.php");
require_once("../models/config.php");

$error = "";

function createRememberCookie($username)
{
    $expire = time() + (86400 * REMEMBER_DAYS);
    $data = $username . "|" . $expire;
    $signature = hash_hmac("sha256", $data, APP_SECRET_KEY);
    $cookieValue = $data . "|" . $signature;
    setcookie("remember_me", $cookieValue, $expire, "/");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "login") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username == "" || $password == "") {
        $error = "Username and Password required!";
    } else {

        if ($username === "admin" && $password === "admin") {
            $_SESSION["username"] = "admin";
            $_SESSION["role"] = "admin";
            createRememberCookie("admin");
            header("Location: ../views/AdminDashboard.php");
            exit();
        }

        $sql = "SELECT * FROM users WHERE name = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {

                if (password_verify($password, $user["password"])) {
                    $_SESSION["username"] = $user["name"];
                    $_SESSION["role"] = "passenger";
                    createRememberCookie($user["name"]);
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
