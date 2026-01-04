<?php
require_once("../controllers/loginvalidation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetroSheba | Login</title>

    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: #f2f2f2;
            overflow: auto; /* ✅ scroll enabled */
        }

        .page {
            width: 100%;
            min-height: 100vh;
            display: flex;
        }

        .left {
            width: 60%;
            min-height: 100vh;
            background-image: url("../assets/metro2.jpg"); /* ✅ image link fixed */
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
            position: sticky;
            top: 0;
        }

        .right {
            width: 40%;
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 10px;
            overflow-y: auto; /* ✅ right side scroll */
        }

        .card {
            width: 90%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.15);
            padding: 18px;
            margin-bottom: 20px;
        }

        .card h1 {
            margin: 0 0 8px;
            font-size: 26px;
            text-align: center;
        }

        .line {
            height: 1px;
            background: #e5e7eb;
            margin: 10px 0 14px;
        }

        .row {
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 11px;
            font-size: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            box-sizing: border-box;
            background: #fafafa;
        }

        input:focus {
            border-color: #a90b0b;
            box-shadow: 0 0 5px rgba(169, 11, 11, 0.2);
            background: white;
        }

        .field-error {
            margin-top: 5px;
            font-size: 13px;
            color: #a10000;
            min-height: 16px;
        }

        .error-box {
            background: #ffe5e5;
            border: 1px solid #ffb3b3;
            color: #a10000;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 13px;
            text-align: center;
        }

        .btn {
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            background: #a90b0b;
            color: #ffffff;
            margin-top: 8px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #000000ff;
        }

        .small {
            font-size: 14px;
            text-align: center;
            margin-top: 14px;
        }

        .small a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: bold;
        }

        .small a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .page {
                flex-direction: column;
            }
            .left {
                width: 100%;
                height: 250px;
                position: relative;
            }
            .right {
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>

<body>
<div class="page">
    <div class="left"></div>

    <div class="right">
        <div class="card">
            <h1>Login</h1>
            <div class="line"></div>

            <?php if (!empty($top_msg)) { ?>
                <div class="error-box"><?php echo e($top_msg); ?></div>
            <?php } ?>

            <form id="loginForm" method="post" action="" autocomplete="off" novalidate>
                <input type="hidden" name="action" value="login">

                <div class="row">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text"
                           value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>"
                           placeholder="Enter username" autocomplete="off">
                    <div class="field-error" id="err_username">
                        <?php echo isset($field_errors["username"]) ? e($field_errors["username"]) : ""; ?>
                    </div>
                </div>

                <div class="row">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password"
                           placeholder="Enter password" autocomplete="new-password">
                    <div class="field-error" id="err_password">
                        <?php echo isset($field_errors["password"]) ? e($field_errors["password"]) : ""; ?>
                    </div>
                </div>

                <button class="btn" type="submit">Login</button>

                <div class="small">
                    Don't have an account?
                    <a href="Register.php">Register</a>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    var form = document.getElementById("loginForm");
    var usernameInput = document.getElementById("username");
    var passwordInput = document.getElementById("password");

    function setErr(id, msg) {
        var el = document.getElementById(id);
        if (el) el.innerHTML = msg;
    }

    function clearErrs() {
        setErr("err_username", "");
        setErr("err_password", "");
    }

    function validateLogin() {
        var ok = true;
        clearErrs();

        var u = usernameInput.value.trim();
        var p = passwordInput.value;

        if (u === "") {
            setErr("err_username", "Username is required.");
            ok = false;
        }

        if (p === "") {
            setErr("err_password", "Password is required.");
            ok = false;
        }

        return ok;
    }

    usernameInput.onblur = validateLogin;
    passwordInput.onblur = validateLogin;

    form.onsubmit = function (e) {
        if (!validateLogin()) {
            e.preventDefault();
        }
    };
</script>

</body>
</html>
