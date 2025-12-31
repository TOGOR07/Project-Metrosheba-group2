<?php
require_once("../controllers/PassengerProfileValidation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Metroseba</title>
    
    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f4f4; }

        .left-section {
            flex: 1;
            background-color: #333;
            background-image: url('../assets/342.jpg'); 
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .left-section::after {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }

        .right-section {
            flex: 1;
            background: white;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .header-left { display: flex; align-items: center; gap: 20px; }
        .back-btn {
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; text-decoration: none; transition: 0.3s;
            padding: 5px; border-radius: 50%;
        }
        .back-btn img { width: 24px; height: 24px; }
        .back-btn:hover { background-color: #f0f0f0; }
        .page-title { font-size: 32px; font-weight: bold; }

        .profile-img-container {
            display: flex; flex-direction: column; align-items: center; gap: 8px;
        }
        .user-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            object-fit: cover; border: 3px solid #ddd;
        }
        .change-photo-btn {
            font-size: 12px; color: #b05b5b; font-weight: bold;
            text-decoration: none; cursor: pointer; border: 1px solid #b05b5b;
            padding: 4px 8px; border-radius: 4px; transition: 0.3s;
        }
        .change-photo-btn:hover { background-color: #b05b5b; color: white; }

        .profile-info { display: flex; flex-direction: column; gap: 25px; }
        .info-group { display: flex; flex-direction: column; }
        .label { font-size: 14px; color: #666; margin-bottom: 5px; }
        .value {
            font-size: 18px; font-weight: bold; color: #000;
            padding: 5px 0; border-bottom: 1px solid #eee; min-height: 30px;
        }

        .edit-btn {
            margin-top: 40px; background-color: #b05b5b; color: white;
            padding: 15px; border: none; border-radius: 8px; font-size: 18px;
            font-weight: bold; cursor: pointer; transition: 0.3s;
            width: 200px; text-align: center; text-decoration: none; display: block; 
        }
        .edit-btn:hover { background-color: #8e4747; }
    </style>
</head>
<body>

    <div class="left-section"></div>

    <div class="right-section">
        <div class="profile-header">
            <div class="header-left">
                <a href="PassengerDashboard.php" class="back-btn">
                    <img src="../assets/arrow.png" alt="Back">
                </a>
                <span class="page-title">Profile</span>
            </div>
            
            <div class="profile-img-container">
                <img src="<?php echo $profile_image; ?>" alt="User" class="user-avatar">
                <a href="EditProfilePicture.php" class="change-photo-btn">Change Photo</a>
            </div>
        </div>

        <div class="profile-info">
            <div class="info-group">
                <span class="label">Name</span>
                <span class="value"><?php echo $name; ?></span>
            </div>
            <div class="info-group">
                <span class="label">Email</span>
                <span class="value"><?php echo $email; ?></span>
            </div>
            <div class="info-group">
                <span class="label">Mobile Number</span>
                <span class="value"><?php echo $mobile; ?></span>
            </div>
            <div class="info-group">
                <span class="label">Gender</span>
                <span class="value"><?php echo $gender; ?></span>
            </div>
            <div class="info-group">
                <span class="label">NID Number</span>
                <span class="value"><?php echo $nid; ?></span>
            </div>
            <div class="info-group">
                <span class="label">Date-Of-Birth</span>
                <span class="value"><?php echo $dob; ?></span>
            </div>

            <a href="EditProfileDetails.php" class="edit-btn">Edit Profile</a>
        </div>
    </div>

</body>
</html>
