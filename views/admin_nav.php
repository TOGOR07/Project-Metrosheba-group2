<?php
// Shared sidebar for the admin pages.
$activePage = $activePage ?? "";
?>
<div class="sidebar">
    <div class="logo-box">
        <img src="../assets/342.jpg" alt="MetroSheba logo" class="logo-img">
        <h3>Metro Admin</h3>
    </div>

    <nav class="menu">
        <a href="AdminDashboard.php" class="<?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
            <img src="../assets/dashboard.png" class="menu-icon" alt=""> Dashboard
        </a>
        <a href="ticketinfo.php" class="<?php echo $activePage === 'tickets' ? 'active' : ''; ?>">
            <img src="../assets/edit.png" class="menu-icon" alt=""> Ticket Sales Info
        </a>
        <a href="../controllers/logout.php">
            <img src="../assets/logout.png" class="menu-icon" alt=""> Logout
        </a>
    </nav>
</div>
