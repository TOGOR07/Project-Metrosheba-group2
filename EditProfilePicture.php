<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Photo - Metroseba</title>
    <style>
       
        @font-face {
            font-family: 'Poppins';
            src: url('assets/Poppins/Poppins-Regular.ttf') format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        
        .container {
            background: white;
            width: 100%;
            max-width: 450px;
            height: 100vh; 
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        
        .header {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 40px;
        }

        .back-btn {
            position: absolute;
            left: 20px;
            cursor: pointer;
            padding: 5px; 
        }
        
        .back-btn img {
            width: 24px;
        }

        .app-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            gap: 30px;
        }

        .page-heading {
            font-size: 22px;
            font-weight: 500;
        }

        
        .image-preview-box {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid #a8dadc;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 250px;
            align-items: center;
        }

        
        .file-upload-btn {
            background: white;
            border: 1px solid #ccc;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .file-upload-btn:hover {
            background-color: #f9f9f9;
        }

       
        input[type="file"] {
            display: none;
        }

        
        .confirm-btn {
            background-color: #b05b5b;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .confirm-btn:hover {
            background-color: #8f4747;
        }

    </style>
</head>
<body>

    <div class="container">
        
        <div class="header">
            <a href="PassengerProfile.html" class="back-btn">
                <img src="assets/arrow.png" alt="Back">
            </a>
            <div class="app-title">METROSEHBA</div>
        </div>

        <div class="content">
            <div class="page-heading">Change Photo</div>

            <div class="image-preview-box">
                <img src="assets/user.png" id="previewImage" alt="Profile Preview">
            </div>

            <div class="btn-group">
                <label for="fileInput" class="file-upload-btn">
                    Choose Image
                </label>
                <input type="file" id="fileInput" accept="image/*" onchange="loadFile(event)">

                <button class="confirm-btn" onclick="saveImage()">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        function loadFile(event) {
            var output = document.getElementById('previewImage');
            if(event.target.files && event.target.files[0]) {
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) 
                }
            }
        }

        function saveImage() {
            var fileInput = document.getElementById('fileInput');
            
            if(fileInput.files.length === 0) {
                alert("Please choose an image first!");
            } else {
                alert("Profile picture updated successfully! (Database logic required)");
                window.location.href = "PassengerProfile.html";
            }
        }
    </script>

</body>
</html>