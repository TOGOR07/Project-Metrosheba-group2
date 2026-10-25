<?php
require_once("../controllers/PassengerProfileValidation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | MetroSheba</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>

<body class="profile">

<div class="left-section"></div>

<div class="right-section">
    <div class="profile-header">
        <div class="header-left">
            <a href="PassengerDashboard.php" class="back-btn">
                <img src="../assets/arrow.png" alt="Back to dashboard">
            </a>
            <span class="page-title">Profile</span>
        </div>

        <div class="profile-img-container">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile picture"
                 class="user-avatar" id="userAvatar">

            <div class="upload-box">
                <label class="visually-hidden" for="profilePicInput">Change profile picture</label>
                <input type="file" id="profilePicInput" accept="image/*">
                <span class="upload-status" id="uploadStatus"></span>
            </div>
        </div>
    </div>

    <div class="profile-info">
        <div class="info-group">
            <span class="label">Name</span>
            <span class="value"><?php echo htmlspecialchars($name); ?></span>
        </div>

        <div class="info-group">
            <span class="label">Email</span>
            <span class="value"><?php echo htmlspecialchars($email); ?></span>
        </div>

        <div class="info-group">
            <span class="label">Mobile Number</span>
            <span class="value"><?php echo htmlspecialchars($mobile); ?></span>
        </div>

        <div class="info-group">
            <span class="label">Gender</span>
            <span class="value"><?php echo htmlspecialchars($gender); ?></span>
        </div>

        <div class="info-group">
            <span class="label">NID Number</span>
            <span class="value"><?php echo htmlspecialchars($nid); ?></span>
        </div>

        <div class="info-group">
            <span class="label">Date Of Birth</span>
            <span class="value"><?php echo htmlspecialchars($dob); ?></span>
        </div>

        <div class="profile-actions">
            <a href="EditProfileDetails.php" class="btn btn-primary">Edit Profile</a>
            <a href="MyTickets.php" class="btn btn-dark">My Tickets</a>
            <a href="../controllers/logout.php" class="btn btn-muted">Logout</a>
        </div>
    </div>
</div>

<script src="../assets/js/metrosheba.js"></script>
<script src="../assets/js/profile-picture.js"></script>
</body>
</html>
