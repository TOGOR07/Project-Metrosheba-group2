<?php
// Shared sidebar for the passenger pages. $activePage is set by the
// including page so the current link is highlighted.
$activePage = $activePage ?? "";
$navLinks = [
    "dashboard" => ["PassengerDashboard.php", "dashboard.png", "Buy Ticket"],
    "tickets"   => ["MyTickets.php", "edit.png", "My Tickets"],
    "profile"   => ["PassengerProfile.php", "user.png", "View Profile"],
    "edit"      => ["EditProfileDetails.php", "pen.png", "Edit Profile"],
];
?>
<div class="sidebar">
    <div class="logo-box">
        <img src="../assets/342.jpg" alt="MetroSheba logo" class="logo-img">
        <h2>Metro Sheba</h2>
    </div>

    <nav class="menu">
        <?php foreach ($navLinks as $key => $link): ?>
            <a href="<?php echo $link[0]; ?>" class="<?php echo $activePage === $key ? 'active' : ''; ?>">
                <img src="../assets/<?php echo $link[1]; ?>" class="menu-icon" alt="">
                <?php echo $link[2]; ?>
            </a>
        <?php endforeach; ?>
        <a href="../controllers/logout.php">
            <img src="../assets/logout.png" class="menu-icon" alt=""> Logout
        </a>
    </nav>
</div>
