<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MetroSheba</title>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      overflow: hidden;
      background: #ffffff;
    }
    .main-box {
      width: 100%;
      height: 100vh;
      display: flex;
    }
    .left {
      width: 65%;
      height: 100vh;
      background-image: url("../assets/metro3.jpeg");
      background-size: cover;
      background-position: center;
      background-color: #cbd5e1;
    }
    .right {
      width: 35%;
      height: 100vh;
      background: #ffffff;
      text-align: center;
      position: relative;
    }
    .top-text {
      position: absolute;
      top: 70px;
      left: 0;
      right: 0;
    }
    .welcome {
      margin: 0;
      font-size: 50px;
      color: #111;
    }
    .title {
      margin: 12px 0 0;
      font-size: 68px;
      font-weight: bold;
      color: #000;
    }
    .middle-text {
      position: absolute;
      top: 38%;
      left: 0;
      right: 0;
    }
    .online {
      margin: 0;
      font-size: 54px;
      font-weight: bold;
      color: #166534;
      font-family: "Times New Roman", serif;
    }
    .ticketing {
      margin: 8px 0 0;
      font-size: 26px;
      color: #dc2626;
      font-style: italic;
      line-height: 1.1;
    }
    .buttons {
      position: absolute;
      bottom: 120px;
      left: 0;
      right: 0;
      display: flex;
      justify-content: center;
      gap: 25px;
    }
    .btn {
      border: none;
      padding: 14px 55px;
      border-radius: 35px;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
      background: #a90b0bff;
      color: white;
      box-shadow: 0 8px 14px rgba(0,0,0,0.25);
      transition: 0.3s;
    }
    .btn:hover {
      background: #000000ff;
    }
  </style>
</head>

<body>
  <div class="main-box">
    <div class="left"></div>

    <div class="right">

      <div class="top-text">
        <p class="welcome">Welcome To</p>
        <p class="title">MetroSheba</p>
      </div>

      <div class="middle-text">
        <p class="online">Online</p>
        <p class="ticketing">Ticketing<br>Platform</p>
      </div>

      <div class="buttons">
        <button class="btn" type="button" onclick="goLogin()">Log in</button>
        <button class="btn" type="button" onclick="goRegister()">Register</button>
      </div>

    </div>
  </div>

  <script>
    function goLogin() {
      window.location.href = "login.php";
    }

    function goRegister() {
      window.location.href = "register.php";
    }
  </script>

</body>
</html>
