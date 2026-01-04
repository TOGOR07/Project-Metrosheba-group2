<?php
require_once("../controllers/PassengerDashboardAuthCheck.php");
require_once("../controllers/PassengerDashboardStationCheck.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard - Metro Seba</title>
    
    <style>
        @font-face { font-family: 'Poppins'; src: url('../assets/Poppins/Poppins-Regular.ttf') format('truetype'); }
        :root { 
            --primary-color: #c06c6c; 
            --primary-dark: #a05555; 
            --light-bg: #f4f7f6; 
            --text-dark: #333; 
            --white: #ffffff; 
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', Arial, sans-serif; }
        body { background-color: var(--light-bg); display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background-color: var(--white); box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
        .logo-box { background-color: var(--primary-color); padding: 20px; color: var(--white); display: flex; align-items: center; gap: 15px; }
        .logo-img { width: 40px; height: 40px; border-radius: 5px; object-fit: contain; background: white; padding: 2px; }
        
        .menu { margin-top: 20px; }
        .menu a { display: flex; align-items: center; padding: 15px 25px; color: var(--text-dark); text-decoration: none; transition: 0.3s; font-weight: 600; font-size: 15px; }
        .menu-icon { width: 24px; height: 24px; margin-right: 15px; object-fit: contain; opacity: 0.7; }
        .menu a:hover .menu-icon, .menu a.active .menu-icon { opacity: 1; }
        .menu a:hover, .menu a.active { background-color: #fcebeb; color: var(--primary-color); border-left: 5px solid var(--primary-color); }
        
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .header { 
            background-color: var(--primary-color); 
            color: var(--white); 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            height: 70px; 
        }

        .welcome-text{
            font-size: 16px;
            font-weight: 600;
        }
        .welcome-text span{
            font-weight: bold;
            text-transform: capitalize;
        }

        .dashboard-container { padding: 30px; display: flex; gap: 30px; height: calc(100vh - 70px); }
        
        .booking-card { flex: 2; background: var(--white); padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; }
        .form-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; }
        
        label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); }
        select, input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; transition: 0.3s; }
        select:focus, input:focus { border-color: var(--primary-color); box-shadow: 0 0 5px rgba(192, 108, 108, 0.3); }
        
        .price-info { background: #fff8f8; padding: 15px; border-radius: 8px; margin-top: 10px; border: 1px dashed var(--primary-color); }
        .notice { color: red; font-size: 12px; margin-top: 5px; font-weight: 500; }

        .btn-confirm { background-color: var(--primary-color); color: white; border: none; padding: 15px; font-size: 18px; font-weight: 600; border-radius: 8px; cursor: pointer; margin-top: 20px; transition: 0.3s; width: 100%; }
        .btn-confirm:hover { background-color: var(--primary-dark); transform: translateY(-2px); }
        
        .map-card { flex: 1; background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow-y: auto; text-align: center; }
        .station-list { list-style: none; position: relative; padding-left: 20px; text-align: left; margin-top: 20px; }
        .station-list::before { content: ''; position: absolute; left: 5px; top: 10px; bottom: 10px; width: 4px; background: #009688; }
        .station-list li { position: relative; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .station-list li::before { content: ''; position: absolute; left: -19px; top: 5px; width: 10px; height: 10px; background: white; border: 3px solid #009688; border-radius: 50%; }

        .message-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 500; }
        .error-box { background-color: #fcebeb; color: #a05555; border: 1px solid #c06c6c; }
        .success-box { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #4caf50; }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo-box">
            <img src="../assets/342.jpg" alt="Metro Logo" class="logo-img">
            <h2>Metro Seba</h2>
        </div>
        <div class="menu">
            <a href="#" class="active"><img src="../assets/dashboard.png" class="menu-icon" alt="Dashboard"> Dashboard</a>
            <a href="PassengerProfile.php"><img src="../assets/user.png" class="menu-icon" alt="Profile"> View Profile</a>
            <a href="EditProfileDetails.php"><img src="../assets/edit.png" class="menu-icon" alt="Edit"> Edit Profile</a>
            <a href="EditProfileDetails.php"><img src="../assets/key.png" class="menu-icon" alt="Password"> Update Password</a>
            <a href="../controllers/logout.php"><img src="../assets/logout.png" class="menu-icon" alt="Logout"> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Passenger Dashboard</h2>

            <div class="welcome-text">
                Welcome, <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </div>

        <div class="dashboard-container">
            <div class="booking-card">
                <h3 style="margin-bottom: 20px; color: var(--primary-color);">Purchase Ticket</h3>
                
                <?php if (!empty($errors)): ?>
                    <div class="message-box error-box">
                        <?php foreach ($errors as $err) { echo "<p>$err</p>"; } ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($successMessage)): ?>
                    <div class="message-box success-box">
                        <p><?php echo $successMessage; ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" onsubmit="return validateTicket()">
                    <div class="form-row">
                        <div class="form-group">
                            <label>From Station</label>
                            <select id="fromStation" name="from_station" onchange="calculatePrice()">
                                <option value="">Select Station</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>To Station</label>
                            <select id="toStation" name="to_station" onchange="calculatePrice()">
                                <option value="">Select Station</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Total Quantity</label>
                        <input type="number" id="quantity" name="quantity"
                               value="<?php echo $quantity > 0 ? $quantity : 1; ?>" 
                               min="1" max="10" 
                               onchange="calculatePrice()" 
                               oninput="calculatePrice()">
                    </div>

                    <div class="price-info">
                        <div class="form-row" style="margin-bottom: 0;">
                            <div class="form-group">
                                <label>Per Ticket Price</label>
                                <input type="text" id="perPrice" value="0 BDT" readonly style="background: #eee;">
                            </div>
                            <div class="form-group">
                                <label>Total Price</label>
                                <input type="text" id="totalPrice" value="0 BDT" readonly style="background: #eee; font-weight: bold; color: var(--primary-color);">
                            </div>
                        </div>
                    </div>
                    
                    <p class="notice">* The ticket Fare Will Increase By 10 TK For Each Station</p>
                    
                    <button type="submit" class="btn-confirm">Confirm Purchase</button>
                </form>
            </div>

            <div class="map-card">
                <h4 style="color: var(--text-dark);">Route Map</h4>
                <ul class="station-list" id="mapList"></ul>
            </div>
        </div>
    </div>

    <script>
        const stations = [
            "Uttara North", "Uttara Center", "Uttara South", 
            "Pallabi", "Mirpur 11", "Mirpur 10", 
            "Kazipara", "Shewrapara", "Agargaon", 
            "Bijoy Sarani", "Farmgate", "Karwan Bazar", 
            "Shahbag", "Dhaka University", "Motijheel"
        ];

        const fromSelect = document.getElementById('fromStation');
        const toSelect = document.getElementById('toStation');
        const mapList = document.getElementById('mapList');

        const selectedFrom = "<?php echo $fromStation; ?>";
        const selectedTo = "<?php echo $toStation; ?>";

        stations.forEach((station) => {
            let option1 = new Option(station, station);
            let option2 = new Option(station, station);
            
            if(station === selectedFrom) option1.selected = true;
            if(station === selectedTo) option2.selected = true;

            fromSelect.add(option1);
            toSelect.add(option2);

            let li = document.createElement('li');
            li.textContent = station;
            mapList.appendChild(li);
        });

        function calculatePrice() {
            let fromVal = document.getElementById('fromStation').value;
            let toVal = document.getElementById('toStation').value;
            let quantity = parseInt(document.getElementById('quantity').value);

            let fromIndex = stations.indexOf(fromVal);
            let toIndex = stations.indexOf(toVal);

            if (fromIndex === -1 || toIndex === -1 || fromIndex === toIndex) {
                document.getElementById('perPrice').value = "0 BDT";
                document.getElementById('totalPrice').value = "0 BDT";
                return;
            }

            let stationsPassed = Math.abs(toIndex - fromIndex);
            let farePerTicket = stationsPassed * 10;
            if(farePerTicket < 20) farePerTicket = 20;

            let total = farePerTicket * quantity;
            document.getElementById('perPrice').value = farePerTicket + " BDT";
            document.getElementById('totalPrice').value = total + " BDT";
        }

        // JS Validation
        function validateTicket() {
            let fromStation = document.getElementById("fromStation").value;
            let toStation = document.getElementById("toStation").value;
            let quantity = document.getElementById("quantity").value;

            if (fromStation === "" || toStation === "") {
                alert("Please select both From and To stations.");
                return false;
            }

            if (fromStation === toStation) {
                alert("Departure and Destination stations cannot be the same!");
                return false;
            }

            if (quantity < 1 || quantity > 10) {
                alert("You can only purchase between 1 and 10 tickets.");
                return false;
            }

            return true;
        }

        calculatePrice();
    </script>
</body>
</html>