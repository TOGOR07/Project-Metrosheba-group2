<?php
session_start();
$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] != 0) {
        $error = "Please select an image to upload.";
    } else {

        $file = $_FILES['profile_pic'];

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowedExt)) {
            $error = "Invalid file type! Only JPG, JPEG, PNG, & GIF allowed.";
        } elseif ($fileSize > 2097152) {
            $error = "File size must be less than 2MB.";
        } else {

            $newFileName = "profile_" . time() . "." . $fileExt;
            $destination = "../assets/uploads/" . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {

                $current_data = isset($_SESSION['user_data']) ? $_SESSION['user_data'] : [];

                $_SESSION['user_data'] = array_merge($current_data, [
                    'profile_image' => $destination
                ]);

                header("Location: PassengerProfile.php");
                exit();
            } else {
                $error = "Failed to upload image.";
            }
        }
    }
}
?>
