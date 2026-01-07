<?php
require_once("../controllers/loginvalidation.php");

$cookieEmail = "";
$rememberChecked = false;

if (isset($_COOKIE["remember_me"])) {
    $parts = explode("|", $_COOKIE["remember_me"]);
    if (count($parts) == 3) {
        $cookieEmail = $parts[0];
        if ($cookieEmail !== "") {
            $rememberChecked = true;
        }
    }
}

$showEmail = $cookieEmail;
if (isset($_POST["email"])) {
    $showEmail = trim($_POST["email"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MetroSheba | Login</title>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      overflow: hidden;
    }

    .php-error-banner {
      width: 100%;
      padding: 12px;
      text-align: center;
      font-weight: bold;
      color: red;
      background: #ffffff;
      border-bottom: 1px solid #ddd;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .page {
      width: 100%;
      height: 100vh;
      display: flex;
      padding-top: <?php echo (!empty($error)) ? "50px" : "0"; ?>;
    }

    .left {
      width: 60%;
      height: 100vh;
      background-image: url("../assets/metro2.jpg");
      background-size: cover;
      background-position: center;
    }

    .right {
      width: 40%;
      height: 100vh;
      background: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      width: 90%;
      max-width: 520px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
      padding: 18px;
    }

    .card h1 {
      margin: 0 0 8px;
      font-size: 26px;
      text-align: center;
    }

    .line {
      height: 1px;
      background: #e5e7eb;
      margin: 10px 0 14px;
    }

    .row {
      margin-bottom: 12px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 11px;
      font-size: 15px;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      outline: none;
      box-sizing: border-box;
    }

    .field-error {
      margin-top: 4px;
      font-size: 12px;
      color: #a10000;
      min-height: 14px;
    }

    .btn {
      border: none;
      padding: 12px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      background: #a90b0b;
      color: #ffffff;
      margin-top: 8px;
    }

    .btn:hover {
      background: #000000;
    }

    .small {
      font-size: 14px;
      text-align: center;
      margin-top: 14px;
    }

    .small a {
      color: #1d4ed8;
      text-decoration: none;
      font-weight: bold;
    }

    .small a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

<?php if (!empty($error)) { ?>
  <div class="php-error-banner"><?php echo $error; ?></div>
<?php } ?>

<div class="page">
  <div class="left"></div>

  <div class="right">
    <div class="card">
      <h1>Login</h1>
      <div class="line"></div>

      <form method="POST" action="" onsubmit="return validateLogin()">
        <input type="hidden" name="action" value="login">

        <div class="row">
          <label>Email</label>
          <input id="email" name="email" type="text" placeholder="Enter email"
                 value="<?php echo htmlspecialchars($showEmail); ?>"
                 autocomplete="off"
                 onblur="checkEmail()" oninput="checkEmail()">
          <div class="field-error" id="err_email"></div>
        </div>

        <div class="row">
          <label>Password</label>
          <input id="password" name="password" type="password" placeholder="Enter password"
                 autocomplete="new-password"
                 onblur="checkPassword()" oninput="checkPassword()">
          <div class="field-error" id="err_password"></div>
        </div>

        <div class="row" style="display:flex; gap:8px; align-items:center;">
          <input type="checkbox" name="remember" value="1" style="width:auto;" <?php if ($rememberChecked) echo "checked"; ?>>
          <label style="margin:0;">Remember me</label>
        </div>

        <button class="btn" type="submit">Login</button>

        <div class="small">
          Don't have an account?
          <a href="register.php">Register</a>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  function setError(id, msg) {
    document.getElementById(id).innerHTML = msg;
  }

  function clearError(id) {
    document.getElementById(id).innerHTML = "";
  }

  function isSimpleEmailValid(email) {
    if (email === "") return false;
    if (email.indexOf(" ") !== -1) return false;

    let atPos = email.indexOf("@");
    let lastAt = email.lastIndexOf("@");

    if (atPos <= 0) return false;
    if (atPos !== lastAt) return false;

    let dotPos = email.lastIndexOf(".");
    if (dotPos === -1) return false;
    if (dotPos < atPos + 2) return false;
    if (dotPos === email.length - 1) return false;

    return true;
  }

  function checkEmail() {
    let email = document.getElementById("email").value.trim();

    if (email === "") {
      setError("err_email", "Email is required.");
      return false;
    }

    if (!isSimpleEmailValid(email)) {
      setError("err_email", "Invalid email format.");
      return false;
    }

    clearError("err_email");
    return true;
  }

  function checkPassword() {
    let password = document.getElementById("password").value;

    if (password === "") {
      setError("err_password", "Password is required.");
      return false;
    }

    clearError("err_password");
    return true;
  }

  function validateLogin() {
    let ok = true;

    if (!checkEmail()) ok = false;
    if (!checkPassword()) ok = false;

    return ok;
  }
  checkEmail();
</script>

</body>
</html>
