<?php
require_once("../controllers/registrationvalidation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MetroSheba | Register</title>

  <style>
    @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', Arial, sans-serif;
      background: #f2f2f2;
      overflow: auto; 
    }

    .page {
      width: 100%;
      min-height: 100vh;
      display: flex;
    }

    .left {
      width: 60%;
      min-height: 100vh;
      background-image: url("../assets/metro5.webp");
      background-size: cover;
      background-position: center;
      background-color: #cbd5e1;
      position: sticky;
      top: 0;
    }

    .right {
      width: 40%;
      min-height: 100vh;
      background: #ffffff;
      display: flex;
      justify-content: center;
      align-items: flex-start; 
      padding: 30px 10px;
      overflow-y: auto;
    }

    .card {
      width: 90%;
      max-width: 500px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
      padding: 20px;
      margin-bottom: 20px;
    }

    .card h1 {
      margin: 0 0 10px;
      font-size: 26px;
      text-align: center;
    }

    .line {
      height: 1px;
      background: #e5e7eb;
      margin: 8px 0 15px;
    }

    .row {
      margin-bottom: 12px;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 14px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      outline: none;
      box-sizing: border-box;
      background: #fafafa;
    }

    input:focus, select:focus {
      border-color: #a90b0b;
      box-shadow: 0 0 5px rgba(169, 11, 11, 0.2);
      background: white;
    }

    .success-box {
      background: #e8fff0;
      border: 1px solid #b6f2c6;
      color: #0b6b2b;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 12px;
      font-size: 14px;
      text-align: center;
    }

    .error-box {
      background: #ffe5e5;
      border: 1px solid #ffb3b3;
      color: #a10000;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 12px;
      font-size: 14px;
      text-align: center;
    }

    .field-error {
      margin-top: 4px;
      font-size: 12px;
      color: #a10000;
      min-height: 14px;
    }

    .field-ok {
      margin-top: 4px;
      font-size: 12px;
      color: #0b6b2b;
      min-height: 14px;
    }

    .actions {
      display: flex;
      gap: 8px;
      margin-top: 6px;
    }

    .btn {
      border: none;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-send {
      background: #111827;
      color: #ffffff;
    }

    .btn-send:hover {
      opacity: 0.85;
    }

    .btn-main {
      background: #a90b0b;
      color: #ffffff;
      width: 100%;
      margin-top: 10px;
      padding: 12px;
      font-size: 15px;
      border-radius: 12px;
    }

    .btn-main:hover {
      opacity: 0.9;
    }

    .small {
      text-align: center;
      margin-top: 12px;
      font-size: 14px;
    }

    .small a {
      color: #1d4ed8;
      font-weight: bold;
      text-decoration: none;
    }

    @media (max-width: 900px) {
      .page {
        flex-direction: column;
      }
      .left {
        width: 100%;
        height: 250px;
        position: relative;
      }
      .right {
        width: 100%;
        min-height: auto;
      }
    }

  </style>
</head>

<body>
  <div class="page">
    <div class="left"></div>

    <div class="right">
      <div class="card">

        <h1>Register</h1>
        <div class="line"></div>

        <?php if (!empty($success_msg)) { ?>
          <div class="success-box">
            <?php echo $success_msg; ?>
            <div style="margin-top:6px;">
              <a href="Login.php" style="font-weight:bold; color:#1d4ed8; text-decoration:none;">Go to Login</a>
            </div>
          </div>
        <?php } ?>

        <?php if (isset($errors["db"])) { ?>
          <div class="error-box"><?php echo $errors["db"]; ?></div>
        <?php } ?>

        <form method="post" autocomplete="off" novalidate onsubmit="return validateRegistration()">
          <input type="hidden" name="action" value="register">

          <div class="row">
            <label>Name</label>
            <input id="name" name="name" type="text" value="<?php echo $_POST['name'] ?? ''; ?>" placeholder="Example: Abdul Rahman">
            <div class="field-error" id="err_name"><?php echo $errors["name"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Email</label>
            <input id="email" name="email" type="email" value="<?php echo $_POST['email'] ?? ''; ?>" placeholder="example@gmail.com">
            <div class="field-error" id="err_email"><?php echo $errors["email"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Mobile Number</label>
            <input id="mobile" name="mobile" type="text" value="<?php echo $_POST['mobile'] ?? ''; ?>" placeholder="01XXXXXXXXX">
            <div class="field-error" id="err_mobile"><?php echo $errors["mobile"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Date of Birth</label>
            <input id="dob" name="dob" type="date" value="<?php echo $_POST['dob'] ?? ''; ?>">
            <div class="field-error" id="err_dob"><?php echo $errors["dob"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>NID No</label>
            <input id="nid" name="nid" type="text" value="<?php echo $_POST['nid'] ?? ''; ?>" placeholder="NID Number">
            <div class="field-error" id="err_nid"><?php echo $errors["nid"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Gender</label>
            <select id="gender" name="gender">
              <option value="">Select</option>
              <option value="Male" <?php if(($_POST['gender'] ?? "")=="Male") echo "selected"; ?>>Male</option>
              <option value="Female" <?php if(($_POST['gender'] ?? "")=="Female") echo "selected"; ?>>Female</option>
              <option value="Other" <?php if(($_POST['gender'] ?? "")=="Other") echo "selected"; ?>>Other</option>
            </select>
            <div class="field-error" id="err_gender"><?php echo $errors["gender"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Password</label>
            <input id="password" name="password" type="password" placeholder="Enter password">
            <div class="field-error" id="err_password"><?php echo $errors["password"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>OTP</label>
            <div class="actions">
              <button class="btn btn-send" type="button" id="btnSendOtp">Send OTP</button>
            </div>
            <input id="otp" name="otp" type="text" placeholder="Enter 6 digit OTP">
            <div class="field-error" id="err_otp"><?php echo $errors["otp"] ?? ""; ?></div>
            <div class="field-ok" id="otpMsg"></div>
          </div>

          <button class="btn btn-main" type="submit">Confirm Registration</button>

          <div class="small">
            Already have an account?
            <a href="Login.php">Login</a>
          </div>
        </form>

      </div>
    </div>
  </div>

<script>
  const btnSendOtp = document.getElementById("btnSendOtp");
  const mobileInput = document.getElementById("mobile");
  const otpMsg = document.getElementById("otpMsg");
  const errOtp = document.getElementById("err_otp");

  btnSendOtp.onclick = function () {
    otpMsg.innerHTML = "";
    errOtp.innerHTML = "";

    const mobile = mobileInput.value.trim();

    if(mobile === ""){
      errOtp.innerHTML = "Mobile number is required to send OTP.";
      return;
    }
    if(mobile.length !== 11 || !mobile.startsWith("01") || isNaN(mobile)) {
       errOtp.innerHTML = "Valid 11 digit mobile number required.";
       return;
    }

    btnSendOtp.disabled = true;
    btnSendOtp.innerHTML = "Sending...";

    const data = new URLSearchParams();
    data.append("ajax", "send_otp");
    data.append("mobile", mobile);

    fetch("Register.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: data.toString()
    })
    .then(res => res.json())
    .then(json => {
      if(json.success){
        otpMsg.innerHTML = json.message;
      } else {
        errOtp.innerHTML = json.message;
      }
    })
    .catch(() => {
      errOtp.innerHTML = "Something went wrong. Try again.";
    })
    .finally(() => {
      btnSendOtp.disabled = false;
      btnSendOtp.innerHTML = "Send OTP";
    });
  };

  function validateRegistration() {
      let isValid = true;

      document.querySelectorAll('.field-error').forEach(e => e.innerHTML = "");

      let name = document.getElementById('name').value.trim();
      let email = document.getElementById('email').value.trim();
      let mobile = document.getElementById('mobile').value.trim();
      let dob = document.getElementById('dob').value;
      let nid = document.getElementById('nid').value.trim();
      let gender = document.getElementById('gender').value;
      let password = document.getElementById('password').value;
      let otp = document.getElementById('otp').value.trim();

      if (name === "") {
          document.getElementById('err_name').innerHTML = "Name is required.";
          isValid = false;
      }

      let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email === "") {
          document.getElementById('err_email').innerHTML = "Email is required.";
          isValid = false;
      } else if (!emailPattern.test(email)) {
          document.getElementById('err_email').innerHTML = "Invalid email format.";
          isValid = false;
      }

      if (mobile === "") {
          document.getElementById('err_mobile').innerHTML = "Mobile number is required.";
          isValid = false;
      } else if (mobile.length !== 11 || !mobile.startsWith("01") || isNaN(mobile)) {
          document.getElementById('err_mobile').innerHTML = "Mobile must be 11 digits and start with 01.";
          isValid = false;
      }

      if (dob === "") {
          document.getElementById('err_dob').innerHTML = "Date of Birth is required.";
          isValid = false;
      }

      if (nid === "") {
          document.getElementById('err_nid').innerHTML = "NID is required.";
          isValid = false;
      } else if (isNaN(nid)) {
          document.getElementById('err_nid').innerHTML = "NID must be numeric.";
          isValid = false;
      }

      if (gender === "") {
          document.getElementById('err_gender').innerHTML = "Gender is required.";
          isValid = false;
      }

      if (password === "") {
          document.getElementById('err_password').innerHTML = "Password is required.";
          isValid = false;
      } else if (password.length < 8) {
          document.getElementById('err_password').innerHTML = "Password must be at least 8 characters.";
          isValid = false;
      }

      if (otp === "") {
          document.getElementById('err_otp').innerHTML = "OTP is required.";
          isValid = false;
      } else if (otp.length !== 6 || isNaN(otp)) {
          document.getElementById('err_otp').innerHTML = "OTP must be 6 digits.";
          isValid = false;
      }

      return isValid;
  }
</script>

</body>
</html>