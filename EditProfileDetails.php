<?php
session_start();


$name = $email = $mobile = $dob = $gender = "";
$errors = [];


if (isset($_SESSION['user_data'])) {
    $data = $_SESSION['user_data'];
    $name = $data['name'] ?? "";
    $email = $data['email'] ?? "";
    $mobile = $data['mobile'] ?? "";
    $dob = $data['dob'] ?? "";
    $gender = $data['gender'] ?? "";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // নাম চেক
    if (empty($_POST["name"])) {
        $errors['name'] = "Name is required";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

   
    if (empty($_POST["mobile"])) {
        $errors['mobile'] = "Mobile number is required";
    } elseif (!is_numeric($_POST["mobile"])) { // পিআরডি অনুযায়ী নম্বর হতে হবে [cite: 4]
        $errors['mobile'] = "Mobile number must be numeric";
    } else {
        $mobile = htmlspecialchars($_POST["mobile"]);
    }

    
    if (empty($_POST["dob"])) {
        $errors['dob'] = "Date of Birth is required";
    } else {
        $dob = htmlspecialchars($_POST["dob"]);
    }

    
    if (empty($_POST["gender"])) {
        $errors['gender'] = "Gender is required";
    } else {
        $gender = htmlspecialchars($_POST["gender"]);
    }

    
    if (empty($errors)) {
        
        $current_data = isset($_SESSION['user_data']) ? $_SESSION['user_data'] : [];
        
        
        $_SESSION['user_data'] = array_merge($current_data, [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'dob' => $dob,
            'gender' => $gender,
            'nid' => $current_data['nid'] ?? 'Not Set' 
        ]);

        header("Location: PassengerProfile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Metroseba</title>
    <style>
        /* আপনার আগের CSS স্টাইল */
        @font-face { font-family: 'Poppins'; src: url('assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: white; display: flex; justify-content: center; min-height: 100vh; }
        .container { width: 100%; max-width: 500px; padding: 20px; display: flex; flex-direction: column; }
        .header { display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 20px; padding-top: 20px; }
        .back-btn { position: absolute; left: 0; cursor: pointer; text-decoration: none; }
        .back-btn img { width: 24px; }
        .app-title { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .title-section { margin-bottom: 30px; }
        .main-title { font-size: 28px; font-weight: bold; color: black; }
        .sub-title { font-size: 14px; color: #555; text-decoration: underline; margin-top: 5px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 18px; font-weight: 500; margin-bottom: 8px; color: black; }
        input[type="text"], input[type="email"], input[type="date"] { width: 100%; padding: 15px; background-color: #f3ecec; border: none; border-radius: 12px; font-size: 16px; outline: none; }
        .gender-options { display: flex; gap: 20px; margin-top: 10px; }
        .radio-label { display: flex; align-items: center; gap: 8px; font-size: 16px; cursor: pointer; font-weight: normal; }
        input[type="radio"] { transform: scale(1.2); accent-color: black; }
        .save-btn { background-color: black; color: white; width: 100%; padding: 15px; border: none; border-radius: 30px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 30px; margin-bottom: 20px; transition: 0.3s; }
        .save-btn:hover { opacity: 0.8; }
        
        
        .error-msg { color: red; font-size: 13px; margin-top: 5px; display: block; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <a href="PassengerProfile.php" class="back-btn"><img src="assets/arrow.png" alt="Back"></a>
            <div class="app-title">METROSEHBA</div>
        </div>

        <div class="title-section">
            <div class="main-title">Edit Profile</div>
            <div class="sub-title">Update your personal details</div>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $name; ?>">
                <?php if(isset($errors['name'])) echo "<span class='error-msg'>".$errors['name']."</span>"; ?>
            </div>

            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <?php if(isset($errors['email'])) echo "<span class='error-msg'>".$errors['email']."</span>"; ?>
            </div>

            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile" value="<?php echo $mobile; ?>">
                <?php if(isset($errors['mobile'])) echo "<span class='error-msg'>".$errors['mobile']."</span>"; ?>
            </div>

            <div class="form-group">
                <label>Date Of Birth</label>
                <input type="date" name="dob" value="<?php echo $dob; ?>">
                <?php if(isset($errors['dob'])) echo "<span class='error-msg'>".$errors['dob']."</span>"; ?>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <div class="gender-options">
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>> Male
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>> Female
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Other" <?php if ($gender == "Other") echo "checked"; ?>> Other
                    </label>
                </div>
                <?php if(isset($errors['gender'])) echo "<span class='error-msg'>".$errors['gender']."</span>"; ?>
            </div>

            <button type="submit" class="save-btn">Save changes</button>
        </form>
    </div>
</body>
</html>