<?php
require_once("api_bootstrap.php");
require_once("../models/passengerprofiledb.php");

requirePost();
$username = requireLogin("passenger");

define("UPLOAD_DIR", __DIR__ . "/../assets/uploads/");
define("MAX_UPLOAD_BYTES", 2 * 1024 * 1024);

if (!isset($_FILES["profile_pic"]) || $_FILES["profile_pic"]["error"] === UPLOAD_ERR_NO_FILE) {
    jsonFail("Please choose an image to upload.");
}

$file = $_FILES["profile_pic"];

if ($file["error"] !== UPLOAD_ERR_OK) {
    jsonFail("Upload failed. The file may be larger than the server allows.");
}

if ($file["size"] > MAX_UPLOAD_BYTES) {
    jsonFail("Image must be smaller than 2 MB.");
}

// Checking the real image type rather than the file name keeps a
// renamed .php from ever landing in the uploads folder.
$imageInfo = @getimagesize($file["tmp_name"]);

if ($imageInfo === false) {
    jsonFail("That file is not a valid image.");
}

$allowed = [
    IMAGETYPE_JPEG => "jpg",
    IMAGETYPE_PNG  => "png",
    IMAGETYPE_GIF  => "gif",
    IMAGETYPE_WEBP => "webp"
];

if (!isset($allowed[$imageInfo[2]])) {
    jsonFail("Only JPG, PNG, GIF and WEBP images are allowed.");
}

if (!is_dir(UPLOAD_DIR) && !mkdir(UPLOAD_DIR, 0775, true)) {
    jsonFail("Upload folder is missing and could not be created.", 500);
}

$fileName = "profile_" . time() . "_" . random_int(1000, 9999) . "." . $allowed[$imageInfo[2]];
$destination = UPLOAD_DIR . $fileName;

if (!move_uploaded_file($file["tmp_name"], $destination)) {
    jsonFail("Could not save the uploaded image.", 500);
}

if (!updateProfileImage($username, $fileName)) {
    unlink($destination);
    jsonFail("Could not save your new picture. Please try again.", 500);
}

jsonReply(true, [
    "image_url" => "../assets/uploads/" . $fileName
], "Profile picture updated.");
?>
