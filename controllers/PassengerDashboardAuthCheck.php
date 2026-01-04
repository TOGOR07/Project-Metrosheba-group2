<?php
session_start();
require_once("../models/config.php");

function verifyRememberCookie()
{
    if (!isset($_COOKIE["remember_me"])) return false;

    $cookie = $_COOKIE["remember_me"];
    $parts = explode("|", $cookie);

    if (count($parts) != 3) return false;

    $username = $parts[0];
    $expire = $parts[1];
    $sig = $parts[2];

    if (time() > (int)$expire) return false;

    $data = $username . "|" . $expire;
    $validSig = hash_hmac("sha256", $data, APP_SECRET_KEY);

    if (!hash_equals($validSig, $sig)) return false;

    return $username;
}

if (isset($_SESSION["username"])) {
    return;
}

$usernameFromCookie = verifyRememberCookie();

if ($usernameFromCookie !== false) {
    $_SESSION["username"] = $usernameFromCookie;
    $_SESSION["role"] = ($usernameFromCookie === "admin") ? "admin" : "passenger";

    if ($_SESSION["role"] === "admin") {
        header("Location: ../views/AdminDashboard.php");
        exit();
    }
    return;
}

header("Location: ../views/Login.php");
exit();
?>
