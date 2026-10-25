<?php
session_start();

$_SESSION = [];
session_destroy();

setcookie("remember_me", "", time() - 3600, "/");

header("Location: ../views/login.php");
exit();
?>
