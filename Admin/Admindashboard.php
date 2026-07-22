<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get total number of events
$event_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
$total_events = mysqli_fetch_assoc($event_result)['total'];

// Step 4: Get total number of registered users
$user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$total_users = mysqli_fetch_assoc($user_result)['total'];

// Step 5: Get total number of registrations
$reg_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations");
$total_registrations = mysqli_fetch_assoc($reg_result)['total'];

// Step 6: Get number of upcoming events
$upcoming_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events WHERE event_date >= CURDATE()");
$upcoming_events = mysqli_fetch_assoc($upcoming_result)['total'];

// Step 7: Get the 5 most recent registrations
$recent_sql = "SELECT users.full_name, users.email, events.event_name, registrations.registered_at
               FROM registrations
               JOIN users ON registrations.user_id = users.id
               JOIN events ON registrations.event_id = events.id
               ORDER BY registrations.registered_at DESC
               LIMIT 5";
$recent_result = mysqli_query($conn, $recent_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | EventHub</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
    background:#f5f7fb;
    color:#333;
}

header{
    background:#1E3A8A;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 70px;
    position:sticky;
    top:0;
    z-index:1000;
}

.logo{
    font-size:26px;
    font-weight:bold;
}

.logo i{
    margin-right:10px;
}

nav a{
    color:white;
    text-decoration:none;
    margin-left:22px;
    font-size:15px;
}

nav a:hover{
    color:#FFD700;
}

.wrapper{
    width:88%;
    max-width:1100px;
    margin:40px auto;
}

.wrapper h1{
    color:#1E3A8A;
    margin-bottom:6px;
}

.wrapper .subtitle{
    color:#666;
    margin-bottom:25px;
}

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:35px;
}

.card{
    background:white;
    padding:22px;
    border-radius:12px;
    box-shadow:0 10px 20px rgba(0,0,0,.06);
    display:flex;
    align-items:center;
    gap:15px;
}

.card i{
    font-size:26px;
    color:#1E3A8A;
    background:#DBEAFE;
    padding:14px;
    border-radius:10px;
}

.card h2{
    font-size:26px;
    color:#1E3A8A;
}

.card p{
    color:#666;
    font-size:13px;
}

.panel{
    background:white;
    border-radius:12px;
    box-shadow:0 10px 20px rgba(0,0,0,.06);
    padding:25px;
}

.panel-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
}

.panel-header h2{
    color:#1E3A8A;
    font-size:19px;
}

.panel-header a{
    color:#1E3A8A;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:12px;
    text-align:left;
    border-bottom:1px solid #eee;
}

th{
    color:#999;
    font-size:12px;
    text-transform:uppercase;
}

.empty{
    text-align:center;
    color:#999;
    padding:30px;
}

.quick-links{
    display:flex;
    gap:15px;
    margin-bottom:30px;
}

.quick-links a{
    background:#1E3A8A;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    font-size:14px;
}

.quick-links a:hover{
    background:#0f172a;
}

.quick-links a.secondary{
    background:white;
    color:#1E3A8A;
    border:2px solid #1E3A8A;
}

@media(max-width:900px){
header{
    flex-direction:column;
    gap:12px;
    padding:20px;
}
.cards{
    grid-template-columns:repeat(2,1fr);
}
}

</style>
</head>
<body>

<header>
    <div class="logo"><i class="fa-solid fa-user-shield"></i> EventHub Admin</div>
    <nav>
        <a href="admindashboard.php">Dashboard</a>
        <a href="adminevents.php">Events</a>
        <a href="adminregistrations.php">Registrations</a>
        <a href="adminlogout.php">Logout</a>
    </nav>
</header>

<div class="wrapper">

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
    <p class="subtitle">Here's what's happening across EventHub.</p>

    <div class="cards">
        <div class="card">
            <i class="fa-solid fa-calendar-days"></i>
            <div>
                <h2><?php echo $total_events; ?></h2>
                <p>Total Events</p>
            </div>
        </div>
        <div class="card">
            <i class="fa-solid fa-clock"></i>
            <div>
                <h2><?php echo $upcoming_events; ?></h2>
                <p>Upcoming Events</p>
            </div>
        </div>
        <div class="card">
            <i class="fa-solid fa-users"></i>
            <div>
                <h2><?php echo $total_users; ?></h2>
                <p>Registered Users</p>
            </div>
        </div>
        <div class="card">
            <i class="fa-solid fa-clipboard-list"></i>
            <div>
                <h2><?php echo $total_registrations; ?></h2>
                <p>Total Registrations</p>
            </div>
        </div>
    </div>

    <div class="quick-links">
        <a href="addevent.php"><i class="fa-solid fa-plus"></i> Add Event</a>
        <a href="adminevents.php" class="secondary">Manage Events</a>
        <a href="adminregistrations.php" class="secondary">View Registrations</a>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Recent Registrations</h2>
            <a href="adminregistrations.php">View All</a>
        </div>

        <?php if (mysqli_num_rows($recent_result) === 0): ?>
            <div class="empty">No registrations yet.</div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Event</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($recent_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                    <td><?php echo date("j M Y", strtotime($row['registered_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

</div>

</body>
</html>