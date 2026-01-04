<?php
require_once("../controllers/AdminDashboardController.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Metro Seba</title>
    
    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        :root { --primary-color: #2c3e50;  --accent-color: #c06c6c; --light-bg: #f4f7f6; --white: #ffffff; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', Arial, sans-serif; }
        body { background-color: var(--light-bg); display: flex; min-height: 100vh; }

        .sidebar { width: 260px; background-color: var(--primary-color); color: var(--white); display: flex; flex-direction: column; }
        .logo-box { padding: 20px; background-color: #1a252f; display: flex; align-items: center; gap: 15px; }
        .logo-img { width: 40px; height: 40px; border-radius: 5px; background: white; padding: 2px; }
        
        .menu { margin-top: 20px; }
        .menu a { display: flex; align-items: center; padding: 15px 25px; color: #ecf0f1; text-decoration: none; transition: 0.3s; font-size: 15px; }
        .menu a:hover, .menu a.active { background-color: var(--accent-color); }
        .menu-icon { width: 20px; margin-right: 15px; filter: invert(1); } 

        .main-content { flex: 1; display: flex; flex-direction: column; }
        .header { background-color: var(--white); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; height: 70px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        
        .stats-container { padding: 30px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .card { background: var(--white); padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid var(--accent-color); }
        .card h3 { font-size: 32px; color: var(--primary-color); margin-bottom: 5px; }
        .card p { color: #777; font-size: 14px; font-weight: 600; }

        .table-container { padding: 0 30px 30px; }
        .table-card { background: var(--white); padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; }
        th { background-color: #f8f9fa; color: var(--primary-color); font-weight: 600; }
        tr:hover { background-color: #f1f1f1; }

        .btn-export { background-color: var(--accent-color); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; text-decoration:none; display:inline-block; }
        .btn-export:hover { opacity: 0.8; }

        .admin-info { display: flex; align-items: center; gap: 10px; }
        .admin-avatar { width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--accent-color); }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-box">
            <img src="../assets/342.jpg" alt="Logo" class="logo-img">
            <h3>Metro Admin</h3>
        </div>
        <div class="menu">
            <a href="AdminDashboard.php" class="active"><img src="../assets/dashboard.png" class="menu-icon"> Dashboard</a>
            <a href="ticketinfo.php"><img src="../assets/edit.png" class="menu-icon"> Ticket Sales Info</a>
            <a href="../controllers/logout.php"><img src="../assets/logout.png" class="menu-icon"> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Dashboard Overview</h2>
            <div class="admin-info">
                <div style="text-align: right;">
                    <strong>Admin</strong><br>
                    <small style="color: gray;">Administrator</small>
                </div>
                <img src="../assets/user.png" class="admin-avatar">
            </div>
        </div>

        <div class="stats-container">
            <div class="card">
                <h3><?php echo $stats['total_tickets']; ?></h3>
                <p>Total Sold Tickets</p>
            </div>
            <div class="card">
                <h3><?php echo $stats['total_profit']; ?> ৳</h3>
                <p>Total Profit</p>
            </div>
        </div>

        <div class="table-container">
            <div class="table-card">
                <div class="table-header">
                    <h3>Customer Information</h3>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>NID</th>
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $c): ?>
                        <tr>
                            <td>#<?php echo $c['id']; ?></td>
                            <td><?php echo $c['name']; ?></td>
                            <td><?php echo $c['email']; ?></td>
                            <td><?php echo $c['mobile_number']; ?></td>
                            <td><?php echo $c['nid']; ?></td>
                            <td><?php echo $c['gender']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
