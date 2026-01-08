<?php
session_start();
require_once("../models/config.php");
require_once("../models/db.php");

function verifyRememberCookie()
{
    if (!isset($_COOKIE["remember_me"])) return false;

    $cookie = $_COOKIE["remember_me"];
    $parts = explode("|", $cookie);

    if (count($parts) != 3) return false;

    $value = $parts[0];   // can be "admin" or an email
    $expire = $parts[1];
    $sig = $parts[2];

    if (time() > (int)$expire) return false;

    $data = $value . "|" . $expire;
    $validSig = hash_hmac("sha256", $data, APP_SECRET_KEY);

    if (!hash_equals($validSig, $sig)) return false;

    return $value;
}

// If already logged in, allow
if (isset($_SESSION["role"]) && isset($_SESSION["username"])) {
    return;
}

$valueFromCookie = verifyRememberCookie();

// No cookie => go login
if ($valueFromCookie === false) {
    header("Location: ../views/login.php");
    exit();
}

// Admin cookie
if ($valueFromCookie === "admin") {
    $_SESSION["username"] = "admin";
    $_SESSION["email"] = "admin";
    $_SESSION["role"] = "admin";
    header("Location: ../views/AdminDashboard.php");
    exit();
}

// Passenger cookie value should be email
$email = $valueFromCookie;

// Load user by email so we can set NAME in session
$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    header("Location: ../views/login.php");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($res);

if (!$user) {
    header("Location: ../views/login.php");
    exit();
}

$_SESSION["username"] = $user["name"];  // name for welcome
$_SESSION["email"] = $user["email"];    // email for identity
$_SESSION["role"] = "passenger";
return;
?>
