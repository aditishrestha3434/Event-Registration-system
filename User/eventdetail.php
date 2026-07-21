<?php
// Step 1: Start session and connect to database
session_start();
$is_logged_in = isset($_SESSION['user_id']);
include 'config/db.php';

// Step 2: Get the event id from the URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Step 3: Fetch the event, along with how many seats are left
$stmt = mysqli_prepare($conn, "SELECT *, (capacity - (SELECT COUNT(*) FROM registrations WHERE registrations.event_id = events.id)) AS seats_left FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

// Step 4: If the event doesn't exist, go back to the events list
if (!$event) {
    header("Location: event.php");
    exit();
}

$seats_left = max((int) $event['seats_left'], 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($event['event_name']); ?> | EventHub</title>

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
    max-width:900px;
    margin:40px auto;
}

.back-link{
    display:inline-block;
    margin-bottom:20px;
    color:#2563EB;
    font-weight:bold;
    text-decoration:none;
}

.back-link:hover{
    text-decoration:underline;
}

.banner img{
    width:100%;
    height:340px;
    object-fit:cover;
    border-radius:15px;
}

.detail-card{
    background:white;
    margin-top:25px;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
}

.detail-card h1{
    color:#1E3A8A;
    margin-bottom:20px;
}

.info{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
    margin-bottom:30px;
    color:#444;
}

.detail-card h2{
    margin-top:25px;
    margin-bottom:12px;
    color:#2563EB;
    font-size:20px;
}

.detail-card p{
    line-height:28px;
    color:#444;
}

.register-box{
    text-align:center;
    margin-top:40px;
}

.register-now{
    background:#2563EB;
    color:white;
    padding:15px 40px;
    border-radius:8px;
    text-decoration:none;
    font-size:18px;
    display:inline-block;
}

.register-now:hover{
    background:#1E40AF;
}

.full-notice{
    background:#fee2e2;
    color:#991b1b;
    padding:15px;
    border-radius:8px;
    text-align:center;
    font-weight:bold;
}

/* @media(max-width:768px){ */

header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}

.wrapper{
    width:92%;
}

.info{
    grid-template-columns:1fr;
}

/* } */

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

<?php if ($is_logged_in): ?>
    <a href="myregistration.php">My Registration</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="registration.php">Register</a>
<?php endif; ?>

</nav>

</header>

<div class="wrapper">

    <a href="event.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Events</a>

    <div class="banner">
        <img src="<?php echo htmlspecialchars($event['image_url']); ?>">
    </div>

    <div class="detail-card">
        <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>

        <div class="info">
            <p><i class="fa-solid fa-calendar"></i> <?php echo date("l, j F Y", strtotime($event['event_date'])); ?></p>
            <p><i class="fa-solid fa-clock"></i> <?php echo date("g:i A", strtotime($event['event_time'])); ?></p>
            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['venue']); ?></p>
            <p><i class="fa-solid fa-users"></i> Seats Left: <?php echo $seats_left; ?> / <?php echo $event['capacity']; ?></p>
        </div>

        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

        <div class="register-box">
            <?php if (!$is_logged_in): ?>
                <a href="login.php" class="register-now">Login to Register</a>
            <?php elseif ($seats_left > 0): ?>
                <a href="registerevent.php?event_id=<?php echo $event['id']; ?>" class="register-now">Register Now</a>
            <?php else: ?>
                <p class="full-notice">This event is fully booked.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>