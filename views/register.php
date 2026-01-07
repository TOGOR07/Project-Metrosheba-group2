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
      overflow: hidden;
    }

    .page {
      width: 100%;
      height: 100vh;
      display: flex;
    }

    .left {
      width: 55%;
      height: 100vh;
      background-image: url("../assets/metro5.webp");
      background-size: cover;
      background-position: center;
      background-color: #cbd5e1;
      position: sticky;
      top: 0;
    }

    .right {
      width: 45%;
      height: 100vh;
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
              <a href="login.php" style="font-weight:bold; color:#1d4ed8; text-decoration:none;">Go to Login</a>
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
            <input id="name" name="name" type="text"
                   value="<?php echo $_POST['name'] ?? ''; ?>"
                   placeholder="Example: Abdul Rahman"
                   onblur="checkName()" oninput="checkName()">
            <div class="field-error" id="err_name"><?php echo $errors["name"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Email</label>
            <input id="email" name="email" type="text"
                   value="<?php echo $_POST['email'] ?? ''; ?>"
                   placeholder="example@gmail.com"
                   onblur="checkEmail()" oninput="checkEmail()">
            <div class="field-error" id="err_email"><?php echo $errors["email"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Mobile Number</label>
            <input id="mobile" name="mobile" type="text"
                   value="<?php echo $_POST['mobile'] ?? ''; ?>"
                   placeholder="01XXXXXXXXX"
                   onblur="checkMobile()" oninput="checkMobile()">
            <div class="field-error" id="err_mobile"><?php echo $errors["mobile"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Date of Birth</label>
            <input id="dob" name="dob" type="date"
                   value="<?php echo $_POST['dob'] ?? ''; ?>"
                   onblur="checkDob()" onchange="checkDob()">
            <div class="field-error" id="err_dob"><?php echo $errors["dob"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>NID No</label>
            <input id="nid" name="nid" type="text"
                   value="<?php echo $_POST['nid'] ?? ''; ?>"
                   placeholder="NID Number"
                   onblur="checkNid()" oninput="checkNid()">
            <div class="field-error" id="err_nid"><?php echo $errors["nid"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Gender</label>
            <select id="gender" name="gender" onchange="checkGender()" onblur="checkGender()">
              <option value="">Select</option>
              <option value="Male" <?php if(($_POST['gender'] ?? "")=="Male") echo "selected"; ?>>Male</option>
              <option value="Female" <?php if(($_POST['gender'] ?? "")=="Female") echo "selected"; ?>>Female</option>
              <option value="Other" <?php if(($_POST['gender'] ?? "")=="Other") echo "selected"; ?>>Other</option>
            </select>
            <div class="field-error" id="err_gender"><?php echo $errors["gender"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>Password</label>
            <input id="password" name="password" type="password"
                   placeholder="Enter password"
                   onblur="checkPassword()" oninput="checkPassword()">
            <div class="field-error" id="err_password"><?php echo $errors["password"] ?? ""; ?></div>
          </div>

          <div class="row">
            <label>OTP</label>
            <div class="actions">
              <button class="btn btn-send" type="button" id="btnSendOtp">Send OTP</button>
            </div>
            <input id="otp" name="otp" type="text"
                   placeholder="Enter 6 digit OTP"
                   onblur="checkOtp()" oninput="checkOtp()">
            <div class="field-error" id="err_otp"><?php echo $errors["otp"] ?? ""; ?></div>
            <div class="field-ok" id="otpMsg"></div>
          </div>

          <button class="btn btn-main" type="submit">Confirm Registration</button>

          <div class="small">
            Already have an account?
            <a href="login.php">Login</a>
          </div>
        </form>

      </div>
    </div>
  </div>

<script>
  const btnSendOtp = document.getElementById("btnSendOtp");
  const mobileInput = document.getElementById("mobile");
  const otpMsg = document.getElementById("otpMsg");

  function setError(id, msg) { document.getElementById(id).innerHTML = msg; }
  function clearError(id) { document.getElementById(id).innerHTML = ""; }

  function isAllDigits(text) {
    if (text === "") return false;
    for (let i = 0; i < text.length; i++) {
      let c = text[i];
      if (c < "0" || c > "9") return false;
    }
    return true;
  }

  function hasDigit(text) {
    for (let i = 0; i < text.length; i++) {
      let c = text[i];
      if (c >= "0" && c <= "9") return true;
    }
    return false;
  }

  function getWords(text) {
    let arr = text.split(" ");
    let words = [];
    for (let i = 0; i < arr.length; i++) {
      let w = arr[i].trim();
      if (w !== "") words.push(w);
    }
    return words;
  }

  function checkName() {
    let name = document.getElementById("name").value.trim();

    if (name === "") {
      setError("err_name", "Name is required.");
      return false;
    }

    if (hasDigit(name)) {
      setError("err_name", "Name cannot contain numbers.");
      return false;
    }

    let words = getWords(name);
    if (words.length !== 2) {
      setError("err_name", "Name must be at least 2 words.");
      return false;
    }

    if (words[0].length < 2 || words[1].length < 2) {
      setError("err_name", "Each word must be at least 2 characters.");
      return false;
    }

    clearError("err_name");
    return true;
  }

  function checkEmail() {
    let email = document.getElementById("email").value.trim();

    if (email === "") {
      setError("err_email", "Email is required.");
      return false;
    }

    if (email.indexOf(" ") !== -1) {
      setError("err_email", "Email cannot contain spaces.");
      return false;
    }

    let atPos = email.indexOf("@");
    let lastAt = email.lastIndexOf("@");
    if (atPos <= 0 || atPos !== lastAt) {
      setError("err_email", "Email must contain one @ and not at the start.");
      return false;
    }

    let dotPos = email.lastIndexOf(".");
    if (dotPos === -1) {
      setError("err_email", "Email must contain a dot (.) after @.");
      return false;
    }

    if (dotPos < atPos + 2) {
      setError("err_email", "Dot (.) must be after @.");
      return false;
    }

    if (dotPos === email.length - 1) {
      setError("err_email", "Email cannot end with dot (.).");
      return false;
    }

    clearError("err_email");
    return true;
  }

  function checkMobile() {
    let mobile = document.getElementById("mobile").value.trim();

    if (mobile === "") {
      setError("err_mobile", "Mobile number is required.");
      return false;
    }
    if (!isAllDigits(mobile)) {
      setError("err_mobile", "Mobile must be numeric.");
      return false;
    }
    if (mobile.length !== 11) {
      setError("err_mobile", "Mobile must be 11 digits.");
      return false;
    }
    if (mobile.substring(0, 2) !== "01") {
      setError("err_mobile", "Mobile must start with 01.");
      return false;
    }

    clearError("err_mobile");
    return true;
  }

  function checkDob() {
    let dob = document.getElementById("dob").value;
    if (dob === "") {
      setError("err_dob", "Date of Birth is required.");
      return false;
    }
    clearError("err_dob");
    return true;
  }

  function checkNid() {
    let nid = document.getElementById("nid").value.trim();

    if (nid === "") {
      setError("err_nid", "NID is required.");
      return false;
    }
    if (!isAllDigits(nid)) {
      setError("err_nid", "NID must be numeric.");
      return false;
    }

    clearError("err_nid");
    return true;
  }

  function checkGender() {
    let gender = document.getElementById("gender").value;
    if (gender === "") {
      setError("err_gender", "Gender is required.");
      return false;
    }
    clearError("err_gender");
    return true;
  }

  function checkPassword() {
    let password = document.getElementById("password").value;

    if (password === "") {
      setError("err_password", "Password is required.");
      return false;
    }
    if (password.length < 8) {
      setError("err_password", "Password must be at least 8 characters.");
      return false;
    }

    clearError("err_password");
    return true;
  }

  function checkOtp() {
    let otp = document.getElementById("otp").value.trim();

    if (otp === "") {
      setError("err_otp", "OTP is required.");
      return false;
    }
    if (!isAllDigits(otp)) {
      setError("err_otp", "OTP must be numeric.");
      return false;
    }
    if (otp.length !== 6) {
      setError("err_otp", "OTP must be 6 digits.");
      return false;
    }

    clearError("err_otp");
    return true;
  }

  btnSendOtp.onclick = function () {
    otpMsg.innerHTML = "";
    clearError("err_otp");

    if (!checkMobile()) return;

    let mobile = mobileInput.value.trim();

    btnSendOtp.disabled = true;
    btnSendOtp.innerHTML = "Sending...";

    const data = new URLSearchParams();
    data.append("ajax", "send_otp");
    data.append("mobile", mobile);

    fetch("register.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: data.toString()
    })
    .then(res => res.json())
    .then(json => {
      if (json.success) {
        otpMsg.innerHTML = json.message;
      } else {
        setError("err_otp", json.message);
      }
    })
    .catch(() => {
      setError("err_otp", "Something went wrong. Try again.");
    })
    .finally(() => {
      btnSendOtp.disabled = false;
      btnSendOtp.innerHTML = "Send OTP";
    });
  };

  function validateRegistration() {
    let ok = true;

    if (!checkName()) ok = false;
    if (!checkEmail()) ok = false;
    if (!checkMobile()) ok = false;
    if (!checkDob()) ok = false;
    if (!checkNid()) ok = false;
    if (!checkGender()) ok = false;
    if (!checkPassword()) ok = false;
    if (!checkOtp()) ok = false;

    return ok;
  }
</script>

</body>
</html>
