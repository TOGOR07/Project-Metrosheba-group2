<?php
require_once("../controllers/loginvalidation.php");

$cookieEmail = "";
$rememberChecked = false;

if (isset($_COOKIE["remember_me"])) {
    $parts = explode("|", $_COOKIE["remember_me"]);
    if (count($parts) == 3 && $parts[0] !== "" && $parts[0] !== "admin") {
        $cookieEmail = $parts[0];
        $rememberChecked = true;
    }
}

$showEmail = isset($_POST["email"]) ? trim($_POST["email"]) : $cookieEmail;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MetroSheba | Login</title>
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body class="auth">
<div class="split">
  <div class="split-image"></div>

  <div class="split-content">
    <div class="card-form">
      <h1>Login</h1>
      <div class="line"></div>

      <?php if (!empty($error)) { ?>
        <div class="message-box error-box"><?php echo htmlspecialchars($error); ?></div>
      <?php } ?>

      <form method="POST" action="" id="loginForm" novalidate>
        <input type="hidden" name="action" value="login">

        <div class="row">
          <label for="email">Email</label>
          <input id="email" name="email" type="text" placeholder="Enter email"
                 value="<?php echo htmlspecialchars($showEmail); ?>"
                 autocomplete="username">
          <span class="field-error" id="err_email"></span>
        </div>

        <div class="row">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Enter password"
                 autocomplete="current-password">
          <span class="field-error" id="err_password"></span>
        </div>

        <div class="row checkbox-row">
          <input id="remember" type="checkbox" name="remember" value="1"
                 <?php if ($rememberChecked) echo "checked"; ?>>
          <label for="remember">Remember me</label>
        </div>

        <button class="btn btn-primary btn-block" type="submit">Login</button>

        <p class="form-footer">
          Don't have an account? <a href="register.php">Register</a>
        </p>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/validators.js"></script>
<script src="../assets/js/login.js"></script>
</body>
</html>
