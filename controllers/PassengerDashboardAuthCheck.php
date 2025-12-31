<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'passenger') {
    header("Location: login.php");
    exit();
}
?>
