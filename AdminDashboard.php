<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Metroseba</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        
        .top-header {
            background-color: #b05b5b;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .header-title {
            font-size: 32px;
            font-weight: bold;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            filter: invert(1);
        }

        
        .container {
            display: flex;
            flex: 1;
        }

        
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            border-right: 1px solid #ddd;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            text-decoration: none;
            color: black;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu-item img {
            width: 24px;
            height: 24px;
        }

        .menu-item.active {
            background-color: #b05b5b;
            color: white;
        }
        
        .menu-item.active img {
            filter: invert(1) brightness(100); 
        }

        .menu-item:hover:not(.active) {
            background-color: #f0f0f0;
        }

        
        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            gap: 40px;
        }

        
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid #ddd;
            min-height: 180px; /* ডাটা না থাকলেও যেন সাইজ ঠিক থাকে */
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-weight: 500;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        
        .data-value {
            font-weight: bold;
            color: #333;
        }

        .right-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 450px;
        }

        
        .stat-card {
            padding: 25px;
            border-radius: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .red-card {
            background-color: #b05b5b;
        }

        .teal-card {
            background-color: #5da39d;
        }

        .stat-number {
            font-size: 24px;
        }

        
        .export-btn {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 10px;
            background: white;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .export-btn:hover {
            background-color: #f9f9f9;
            border-color: #b05b5b;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .export-btn h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .export-btn p {
            font-size: 12px;
            color: #666;
        }

        
        .logout-box {
            margin-top: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background: white;
        }

        .logout-btn {
            background-color: #5da39d;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background-color: #4a8b85;
        }

    </style>
</head>
<body>

    <div class="top-header">
        <div class="header-title">Admin Dashboard</div>
        <div class="admin-profile">
            <span>Admin</span>
            <img src="assets/user.png" alt="Admin Icon">
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <a href="#" class="menu-item active">
                <img src="assets/dashboard.png" alt="Dashboard">
                Dashboard
            </a>
            <a href="#" class="menu-item">
                <img src="assets/user.png" alt="Profile">
                View Profile
            </a>
            <a href="#" class="menu-item">
                <img src="assets/edit.png" alt="Edit">
                Edit Profile
            </a>
            <a href="#" class="menu-item">
                <img src="assets/password.png" alt="Password">
                Update Password
            </a>
            <a href="#" class="menu-item">
                <img src="assets/logout.png" alt="Logout">
                Logout
            </a>
        </div>

        <div class="main-content">
            
            <div style="display: flex; flex-direction: column; gap: 20px; width: 100%; max-width: 400px;">
                
                <div class="info-card">
                    <div class="info-row">
                        <span>Customer Id</span> 
                        <span class="data-value" id="customer-id">--</span>
                    </div>
                    <div class="info-row">
                        <span>Customer Name</span> 
                        <span class="data-value" id="customer-name">--</span>
                    </div>
                    <div class="info-row">
                        <span>Customer Email</span> 
                        <span class="data-value" id="customer-email">--</span>
                    </div>
                    <div class="info-row">
                        <span>Total booked Tickets</span> 
                        <span class="data-value" id="customer-tickets">--</span>
                    </div>
                </div>

                <button class="export-btn" onclick="generateReport()">
                    <h4>Export Report</h4>
                    <p>Click here to generate and view all customer information data from the database.</p>
                </button>
            </div>

            <div class="right-section">
                <div class="stat-card red-card">
                    <span>Total Booked Tickets</span>
                    <span class="stat-number" id="total-booked-count">0</span>
                </div>

                <div class="stat-card teal-card">
                    <span>Total Revenue</span>
                    <span class="stat-number" id="total-revenue">0 BDT</span>
                </div>

                <div class="logout-box">
                    <div style="font-weight: bold; margin-bottom: 5px;">Logout</div>
                    <button class="logout-btn">Logout</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        
    </script>

</body>
</html>