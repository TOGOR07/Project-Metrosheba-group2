<?php
require_once("../controllers/EditProfilePictureValidation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Profile Photo - Metroseba</title>
    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { width: 100%; max-width: 400px; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .file-input { margin-bottom: 20px; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .upload-btn { background-color: black; color: white; width: 100%; padding: 12px; border: none; border-radius: 25px; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .upload-btn:hover { opacity: 0.8; }
        .back-link { margin-top: 15px; display: block; color: #555; text-decoration: none; font-size: 14px; }
        .error-msg { color: red; font-size: 14px; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Change Profile Photo</div>

        <?php if (!empty($error)) echo "<span class='error-msg'>$error</span>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_pic" class="file-input" accept="image/*">
            <button type="submit" class="upload-btn">Upload Photo</button>
        </form>

        <a href="PassengerProfile.php" class="back-link">Cancel and Go Back</a>
    </div>
</body>
</html>
