/*
 * Profile page: uploads a new picture over AJAX and swaps the avatar
 * in place, with a local preview shown before the upload finishes.
 */

document.addEventListener("DOMContentLoaded", function () {
    var input = document.getElementById("profilePicInput");

    if (!input) {
        return;
    }

    input.addEventListener("change", function () {
        if (input.files && input.files[0]) {
            previewAndUpload(input.files[0]);
        }
    });
});

function previewAndUpload(file) {
    var status = document.getElementById("uploadStatus");
    var avatar = document.getElementById("userAvatar");

    status.className = "upload-status";
    status.textContent = "Uploading...";

    // Check on this side too, so an obviously wrong file never
    // travels to the server.
    if (file.size > 2 * 1024 * 1024) {
        setStatus(status, "Image must be smaller than 2 MB.", false);
        return;
    }

    if (file.type.indexOf("image/") !== 0) {
        setStatus(status, "Please choose an image file.", false);
        return;
    }

    var previousSrc = avatar.src;
    var reader = new FileReader();

    reader.onload = function (event) {
        avatar.src = event.target.result;
    };

    reader.readAsDataURL(file);

    var formData = new FormData();
    formData.append("profile_pic", file);

    ajaxRequest("POST", "../controllers/profile_picture_api.php", formData, function (response, ok) {
        if (!ok) {
            // The upload failed, so put the old picture back rather
            // than leaving a preview that was never saved.
            avatar.src = previousSrc;
            setStatus(status, response.message || "Upload failed.", false);
            return;
        }

        avatar.src = response.image_url;
        setStatus(status, response.message, true);
    });
}

function setStatus(element, message, isSuccess) {
    element.textContent = message;
    element.className = "upload-status " + (isSuccess ? "ok" : "bad");
}
