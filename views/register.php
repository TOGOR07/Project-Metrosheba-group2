<?php
require_once("../controllers/registrationvalidation.php");

// Small helper so repopulated values are always escaped on the way out.
function old($field)
{
    return htmlspecialchars($_POST[$field] ?? "", ENT_QUOTES);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MetroSheba | Register</title>
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body class="auth">
<div class="split">
  <div class="split-image"></div>

  <div class="split-content">
    <div class="card-form">
      <h1>Register</h1>
      <div class="line"></div>

      <?php if (!empty($success_msg)) { ?>
        <div class="message-box success-box">
          <?php echo htmlspecialchars($success_msg); ?>
          <p style="margin-top:6px;"><a href="login.php">Go to Login</a></p>
        </div>
      <?php } ?>

      <?php if (isset($errors["db"])) { ?>
        <div class="message-box error-box"><?php echo htmlspecialchars($errors["db"]); ?></div>
      <?php } ?>

      <form method="post" id="registerForm" autocomplete="off" novalidate>
        <input type="hidden" name="action" value="register">

        <div class="row">
          <label for="name">Name</label>
          <input id="name" name="name" type="text" value="<?php echo old('name'); ?>"
                 placeholder="Example: Abdul Rahman">
          <span class="field-error" id="err_name"><?php echo htmlspecialchars($errors["name"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="email">Email</label>
          <input id="email" name="email" type="text" value="<?php echo old('email'); ?>"
                 placeholder="example@gmail.com">
          <span class="field-error" id="err_email"><?php echo htmlspecialchars($errors["email"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="mobile">Mobile Number</label>
          <input id="mobile" name="mobile" type="text" value="<?php echo old('mobile'); ?>"
                 placeholder="01XXXXXXXXX" maxlength="11">
          <span class="field-error" id="err_mobile"><?php echo htmlspecialchars($errors["mobile"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="dob">Date of Birth</label>
          <input id="dob" name="dob" type="date" value="<?php echo old('dob'); ?>">
          <span class="field-error" id="err_dob"><?php echo htmlspecialchars($errors["dob"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="nid">NID No</label>
          <input id="nid" name="nid" type="text" value="<?php echo old('nid'); ?>"
                 placeholder="NID Number">
          <span class="field-error" id="err_nid"><?php echo htmlspecialchars($errors["nid"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="gender">Gender</label>
          <select id="gender" name="gender">
            <option value="">Select</option>
            <?php
            foreach (["Male", "Female", "Other"] as $option) {
                $selected = (($_POST['gender'] ?? "") === $option) ? " selected" : "";
                echo "<option value=\"$option\"$selected>$option</option>";
            }
            ?>
          </select>
          <span class="field-error" id="err_gender"><?php echo htmlspecialchars($errors["gender"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="At least 8 characters">
          <span class="field-error" id="err_password"><?php echo htmlspecialchars($errors["password"] ?? ""); ?></span>
        </div>

        <div class="row">
          <label for="otp">OTP Verification</label>
          <div class="otp-row">
            <input id="otp" name="otp" type="text" placeholder="Enter 6 digit OTP" maxlength="6">
            <button class="btn btn-dark" type="button" id="btnSendOtp">Send OTP</button>
          </div>
          <span class="field-error" id="err_otp"><?php echo htmlspecialchars($errors["otp"] ?? ""); ?></span>
          <span class="field-error" id="otpMsg" style="color:#2e7d32;"></span>
        </div>

        <button class="btn btn-primary btn-block" type="submit">Confirm Registration</button>

        <p class="form-footer">
          Already have an account? <a href="login.php">Login</a>
        </p>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/validators.js"></script>
<script src="../assets/js/register.js"></script>
</body>
</html>
