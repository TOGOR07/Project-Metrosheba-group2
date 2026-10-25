<?php
require_once("../controllers/EditProfileDetailsValidation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | MetroSheba</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>

<body class="edit-profile">

<div class="form-container">

    <div class="header">
        <a href="PassengerProfile.php" class="back-btn">
            <img src="../assets/arrow.png" alt="Back to profile">
        </a>
        <div class="app-title">MetroSheba</div>
    </div>

    <div class="title-section">
        <div class="main-title">Edit Profile</div>
        <div class="sub-title">Update your personal details</div>
    </div>

    <?php if (isset($errors['db'])): ?>
        <div class="message-box error-box"><?php echo htmlspecialchars($errors['db']); ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="editProfileForm" novalidate>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="field-error" id="err_name"><?php echo htmlspecialchars($errors['name'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="field-error" id="err_email"><?php echo htmlspecialchars($errors['email'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="text" id="mobile" name="mobile" maxlength="11"
                   value="<?php echo htmlspecialchars($mobile); ?>">
            <span class="field-error" id="err_mobile"><?php echo htmlspecialchars($errors['mobile'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            <span class="field-error" id="err_dob"><?php echo htmlspecialchars($errors['dob'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label for="nid">NID Number</label>
            <input type="text" id="nid" name="nid" value="<?php echo htmlspecialchars($nid); ?>">
            <span class="field-error" id="err_nid"><?php echo htmlspecialchars($errors['nid'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <div class="gender-options">
                <?php foreach (["Male", "Female", "Other"] as $option): ?>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="<?php echo $option; ?>"
                            <?php echo $gender === $option ? "checked" : ""; ?>>
                        <?php echo $option; ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <span class="field-error" id="err_gender"><?php echo htmlspecialchars($errors['gender'] ?? ""); ?></span>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Leave blank to keep your current password">
            <span class="field-error" id="err_password"><?php echo htmlspecialchars($errors['password'] ?? ""); ?></span>
        </div>

        <button type="submit" class="btn btn-dark btn-block save-btn">Save Changes</button>
    </form>
</div>

<script src="../assets/js/validators.js"></script>
<script src="../assets/js/edit-profile.js"></script>
</body>
</html>
