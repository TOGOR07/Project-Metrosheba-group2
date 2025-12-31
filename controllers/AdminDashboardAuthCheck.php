<?php
session_start();

// ✅ যদি admin login না থাকে তাহলে login page এ নিয়ে যাবে
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
