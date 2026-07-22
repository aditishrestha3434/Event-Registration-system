<?php
// Step 1: Start session and require login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$is_logged_in = true;

// Step 2: Connect to database
<<<<<<< HEAD
include '../config/db.php';
=======
include 'config/db.php';
>>>>>>> a49a4951ba294234c7add2e85f8f402cbe966ed3

// Step 3: Fetch this user's registrations, joined with event details
$stmt = mysqli_prepare($conn, "SELECT registrations.id, registrations.status, registrations.registered_at,
                                       events.id AS event_id, events.event_name, events.event_date, events.venue
                                FROM registrations
                                JOIN events ON registrations.event_id = events.id
                                WHERE registrations.user_id = ?
                                ORDER BY events.event_date ASC");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Registrations | EventHub</title>

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
    background:#2563EB;
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
    font-size:30px;
    font-weight:bold;
}

.logo i{
    margin-right:10px;
}

nav a{
    color:white;
    text-decoration:none;
    margin-left:25px;
    font-size:16px;
    transition:.3s;
}

nav a:hover{
    color:#FFD700;
}

.wrapper{
    width:88%;
    max-width:1000px;
    margin:40px auto;
}

.wrapper h1{
    color:#1E3A8A;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 20px rgba(0,0,0,.08);
}

th, td{
    padding:15px;
    text-align:center;
    border-bottom:1px solid #eee;
}

th{
    background:#2563EB;
    color:white;
}

.badge{
    padding:6px 14px;
    border-radius:20px;
    color:#fff;
    font-size:13px;
    font-weight:600;
}

.badge.confirmed{ background:#22C55E; }
.badge.pending{ background:#F59E0B; }

.cancel-btn{
    padding:8px 16px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background:#EF4444;
    color:white;
}

.cancel-btn:hover{
    background:#DC2626;
}

.empty{
    background:white;
    padding:40px;
    border-radius:12px;
    text-align:center;
    color:#666;
}

.alert{
    padding:14px;
    border-radius:8px;
    margin-bottom:20px;
}

.alert.success{ background:#dcfce7; color:#166534; }

@media(max-width:768px){
header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}
.wrapper{
    width:94%;
}
table{
    font-size:13px;
}
}

</style>
</head>
<body>

<header>

<div class="logo">
<i class="fa-solid fa-calendar-days"></i>
SmartEvents
</div>

<nav>
<a href="index.php">Home</a>
<a href="event.php">Events</a>
<a href="myregistration.php">My Registration</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</nav>

</header>

<div class="wrapper">

    <h1>My Registered Events</h1>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert success">You're registered! It shows up in the table below.</div>
    <?php endif; ?>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert success">Registration cancelled.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) === 0): ?>

        <div class="empty">
            You haven't registered for any events yet. <a href="event.php" style="color:#2563EB; font-weight:bold;">Browse events</a>.
        </div>

    <?php else: ?>

        <table>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php $is_past = strtotime($row['event_date']) < strtotime(date('Y-m-d')); ?>
            <tr>
                <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                <td><?php echo date("j F Y", strtotime($row['event_date'])); ?></td>
                <td><?php echo htmlspecialchars($row['venue']); ?></td>
                <td>
                    <?php if ($is_past): ?>
                        <span class="badge" style="background:#6366F1;">Completed</span>
                    <?php elseif ($row['status'] === 'Confirmed'): ?>
                        <span class="badge confirmed">Confirmed</span>
                    <?php else: ?>
                        <span class="badge pending">Pending</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="eventdetail.php?id=<?php echo $row['event_id']; ?>" style="margin-right:8px; color:#2563EB; font-weight:600;">View</a>
                    <?php if (!$is_past): ?>
                        <a href="cancelregistration.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Cancel this registration?');">
                            <button class="cancel-btn" type="button">Cancel</button>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

    <?php endif; ?>

</div>

</body>
</html>