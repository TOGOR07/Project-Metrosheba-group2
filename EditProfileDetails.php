<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Metroseba</title>
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
            background-color: white;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 20px;
            padding-top: 20px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn img {
            width: 24px;
        }

        .app-title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* টাইটেল সেকশন */
        .title-section {
            margin-bottom: 30px;
        }

        .main-title {
            font-size: 28px;
            font-weight: bold;
            color: black;
        }

        .sub-title {
            font-size: 14px;
            color: #555;
            text-decoration: underline;
            margin-top: 5px;
        }

        
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 8px;
            color: black;
        }

       
        input[type="text"], 
        input[type="email"], 
        input[type="date"] {
            width: 100%;
            padding: 15px;
            background-color: #f3ecec;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
        }

        
        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: normal;
        }

        input[type="radio"] {
            transform: scale(1.2);
            accent-color: black;
        }

        
        .save-btn {
            background-color: black;
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 30px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .save-btn:hover {
            opacity: 0.8;
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

        <div class="title-section">
            <div class="main-title">Edit Profile</div>
            <div class="sub-title">Update your personal details</div>
        </div>

        <form id="editForm">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" id="edit_name" name="name">
            </div>

            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" id="edit_email" name="email">
            </div>

            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" id="edit_mobile" name="mobile">
            </div>

            <div class="form-group">
                <label>Date Of Birth</label>
                <input type="date" id="edit_dob" name="dob">
            </div>

            <div class="form-group">
                <label>Gender</label>
                <div class="gender-options">
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Male" id="gender_male"> Male
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Female" id="gender_female"> Female
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Other" id="gender_other"> other
                    </label>
                </div>
            </div>

            <button type="button" class="save-btn" onclick="saveData()">Save changes</button>
        </form>

    </div>

    <script>
       
    </script>

</body>
</html>