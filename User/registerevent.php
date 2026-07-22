<?php
// Step 1: Start session and check the user is logged in
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Step 2: Connect to database
include 'config/db.php';

// Step 3: Get the event id from the URL
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : 0;

// Step 4: Fetch the event, along with seats left
$stmt = mysqli_prepare($conn, "SELECT *, (capacity - (SELECT COUNT(*) FROM registrations WHERE registrations.event_id = events.id)) AS seats_left FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

// Step 5: If the event doesn't exist, go back to the events list
if (!$event) {
    header("Location: event.php");
    exit();
}

// Step 6: Check if this user already registered for this event
$check_stmt = mysqli_prepare($conn, "SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
mysqli_stmt_bind_param($check_stmt, "ii", $_SESSION['user_id'], $event_id);
mysqli_stmt_execute($check_stmt);
$already_registered = mysqli_num_rows(mysqli_stmt_get_result($check_stmt)) > 0;

$seats_left = max((int) $event['seats_left'], 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register for <?php echo htmlspecialchars($event['event_name']); ?> | EventHub</title>
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
    width:80%;
    max-width:700px;
    margin:40px auto;
}

.event-summary{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
    margin-bottom:25px;
}

.event-summary img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.event-summary .content{
    padding:22px;
}

.event-summary h2{
    color:#1E3A8A;
    margin-bottom:12px;
}

.event-summary p{
    color:#555;
    margin:6px 0;
}

form{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
}

.input-box{
    margin-bottom:18px;
}

.input-box label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

.input-box input,
.input-box textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}

.register-btn{
    width:100%;
    margin-top:10px;
    background:#2563EB;
    color:white;
    padding:15px;
    border:none;
    border-radius:8px;
    font-size:17px;
    cursor:pointer;
}

.register-btn:hover{
    background:#1E3A8A;
}

.already-box, .full-box{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
    text-align:center;
}

.already-box i, .full-box i{
    font-size:40px;
    color:#2563EB;
    margin-bottom:15px;
}

@media(max-width:768px){
header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}
.wrapper{
    width:92%;
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

    <div class="event-summary">
        <img src="<?php echo htmlspecialchars($event['image_url']); ?>">
        <div class="content">
            <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
            <p><i class="fa-solid fa-calendar"></i> <?php echo date("l, j F Y", strtotime($event['event_date'])); ?></p>
            <p><i class="fa-solid fa-clock"></i> <?php echo date("g:i A", strtotime($event['event_time'])); ?></p>
            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['venue']); ?></p>
        </div>
    </div>

    <?php if ($already_registered): ?>

        <div class="already-box">
            <i class="fa-solid fa-circle-check"></i>
            <h2 style="color:#1E3A8A; margin-bottom:8px;">You're already registered for this event</h2>
            <p style="color:#666;">Check <a href="myregistration.php" style="color:#2563EB; font-weight:bold;">My Registration</a> for details.</p>
        </div>

    <?php elseif ($seats_left <= 0): ?>

        <div class="full-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h2 style="color:#1E3A8A;">This event is fully booked</h2>
        </div>

    <?php else: ?>

        <h2 style="color:#1E3A8A; margin-bottom:15px;">Confirm Your Registration</h2>

        <form action="registeraction.php" method="POST">

            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

            <div class="input-box">
                <label>Message (optional)</label>
                <textarea name="message" rows="4" placeholder="Any message or note for the organizer..."></textarea>
            </div>

            <button class="register-btn" type="submit">Confirm Registration</button>

        </form>

    <?php endif; ?>

</div>

</body>
</html>