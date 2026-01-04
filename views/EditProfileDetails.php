<?php
require_once("../controllers/EditProfileDetailsValidation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Metroseba</title>
    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: white; display: flex; justify-content: center; min-height: 100vh; overflow-y:auto; }

        .container { width: 100%; max-width: 500px; padding: 20px; display: flex; flex-direction: column; }
        .header { display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 20px; padding-top: 20px; }
        .back-btn { position: absolute; left: 0; cursor: pointer; text-decoration: none; }
        .back-btn img { width: 24px; }
        .app-title { font-size: 20px; font-weight: bold; text-transform: uppercase; }

        .title-section { margin-bottom: 30px; }
        .main-title { font-size: 28px; font-weight: bold; color: black; }
        .sub-title { font-size: 14px; color: #555; text-decoration: underline; margin-top: 5px; }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 16px; font-weight: 500; margin-bottom: 6px; color: black; }
        input[type="text"], input[type="email"], input[type="date"], input[type="password"] {
            width: 100%; padding: 15px; background-color: #f3ecec; border: none; border-radius: 12px; font-size: 15px; outline: none;
        }

        .gender-options { display: flex; gap: 20px; margin-top: 10px; }
        .radio-label { display: flex; align-items: center; gap: 8px; font-size: 16px; cursor: pointer; font-weight: normal; }
        input[type="radio"] { transform: scale(1.2); accent-color: black; }

        .save-btn { background-color: black; color: white; width: 100%; padding: 15px; border: none; border-radius: 30px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .save-btn:hover { opacity: 0.8; }

        .error-msg { color: red; font-size: 13px; margin-top: 5px; display: block; }
        .success-msg { background:#e8fff0; border:1px solid #b6f2c6; padding:10px; border-radius:10px; margin-bottom:15px; color:#0b6b2b; text-align:center; }
        .db-error { background:#ffe5e5; border:1px solid #ffb3b3; padding:10px; border-radius:10px; margin-bottom:15px; color:#a10000; text-align:center; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <a href="PassengerProfile.php" class="back-btn">
            <img src="../assets/arrow.png" alt="Back">
        </a>
        <div class="app-title">METROSEHBA</div>
    </div>

    <div class="title-section">
        <div class="main-title">Edit Profile</div>
        <div class="sub-title">Update your personal details</div>
    </div>

    <?php if(isset($errors['db'])) echo "<div class='db-error'>".$errors['db']."</div>"; ?>

    <form method="POST" action="" onsubmit="return validateEditProfile()">

        <div class="form-group">
            <label>Name</label>
            <input id="name" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error-msg" id="err_name"><?php if(isset($errors['name'])) echo $errors['name']; ?></span>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error-msg" id="err_email"><?php if(isset($errors['email'])) echo $errors['email']; ?></span>
        </div>

        <div class="form-group">
            <label>Mobile Number</label>
            <input id="mobile" type="text" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
            <span class="error-msg" id="err_mobile"><?php if(isset($errors['mobile'])) echo $errors['mobile']; ?></span>
        </div>

        <div class="form-group">
            <label>Date Of Birth</label>
            <input id="dob" type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            <span class="error-msg" id="err_dob"><?php if(isset($errors['dob'])) echo $errors['dob']; ?></span>
        </div>

        <div class="form-group">
            <label>NID Number</label>
            <input id="nid" type="text" name="nid" value="<?php echo htmlspecialchars($nid); ?>">
            <span class="error-msg" id="err_nid"><?php if(isset($errors['nid'])) echo $errors['nid']; ?></span>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <div class="gender-options">
                <label class="radio-label">
                    <input type="radio" name="gender" value="Male" <?php if($gender=="Male") echo "checked"; ?>> Male
                </label>
                <label class="radio-label">
                    <input type="radio" name="gender" value="Female" <?php if($gender=="Female") echo "checked"; ?>> Female
                </label>
                <label class="radio-label">
                    <input type="radio" name="gender" value="Other" <?php if($gender=="Other") echo "checked"; ?>> Other
                </label>
            </div>
            <span class="error-msg" id="err_gender"><?php if(isset($errors['gender'])) echo $errors['gender']; ?></span>
        </div>

        <div class="form-group">
            <label>New Password (optional)</label>
            <input id="password" type="password" name="password" placeholder="Leave empty if you don't want to change">
            <span class="error-msg" id="err_password"><?php if(isset($errors['password'])) echo $errors['password']; ?></span>
        </div>

        <button type="submit" class="save-btn">Save changes</button>
    </form>

</div>

<script>
    function validateEditProfile() {
        let isValid = true;
        
        document.querySelectorAll('.error-msg').forEach(e => {
            e.innerHTML = ""; 
        });

        let name = document.getElementById("name").value.trim();
        let email = document.getElementById("email").value.trim();
        let mobile = document.getElementById("mobile").value.trim();
        let dob = document.getElementById("dob").value;
        let nid = document.getElementById("nid").value.trim();
        let password = document.getElementById("password").value;

        if (name === "") {
            document.getElementById("err_name").innerHTML = "Name is required";
            isValid = false;
        }

        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            document.getElementById("err_email").innerHTML = "Email is required";
            isValid = false;
        } else if (!emailPattern.test(email)) {
            document.getElementById("err_email").innerHTML = "Invalid email format";
            isValid = false;
        }

        if (mobile === "") {
            document.getElementById("err_mobile").innerHTML = "Mobile number is required";
            isValid = false;
        } else if (mobile.length !== 11 || !mobile.startsWith("01") || isNaN(mobile)) {
            document.getElementById("err_mobile").innerHTML = "Mobile must be 11 digits and start with 01";
            isValid = false;
        }

        if (dob === "") {
            document.getElementById("err_dob").innerHTML = "Date of Birth is required";
            isValid = false;
        }

        if (nid === "") {
            document.getElementById("err_nid").innerHTML = "NID is required";
            isValid = false;
        } else if (isNaN(nid)) {
            document.getElementById("err_nid").innerHTML = "NID must be numeric";
            isValid = false;
        }
        
        if (password !== "" && password.length < 8) {
            document.getElementById("err_password").innerHTML = "New password must be at least 8 characters";
            isValid = false;
        }

        return isValid;
    }
</script>

</body>
</html>