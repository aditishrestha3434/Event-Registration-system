<?php
// Step 1: Start the session so we can check if the user is logged in
session_start();
$is_logged_in = isset($_SESSION['user_id']);

// Step 2: Connect to the database

include '../config/db.php';

// Step 3: Check if a search term was submitted
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Step 4: Fetch upcoming events, filtered by search if given
if (!empty($search)) {
    $stmt = mysqli_prepare($conn, "SELECT *, (capacity - (SELECT COUNT(*) FROM registrations WHERE registrations.event_id = events.id)) AS seats_left FROM events WHERE event_date >= CURDATE() AND (event_name LIKE ? OR venue LIKE ?) ORDER BY event_date ASC");
    $like = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $events_result = mysqli_stmt_get_result($stmt);
} else {
    $events_result = mysqli_query($conn, "SELECT *, (capacity - (SELECT COUNT(*) FROM registrations WHERE registrations.event_id = events.id)) AS seats_left FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Event Registration System</title>
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

/* Header */

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

/* Hero */

.hero{
    text-align:center;
    padding:60px 8%;
}

.hero h1{
    font-size:45px;
    color:#1E3A8A;
    margin-bottom:15px;
}

.hero p{
    color:#666;
    font-size:18px;
    margin-bottom:30px;
}

/* Search */

.search-box{
    display:flex;
    justify-content:center;
    margin-top:20px;
}

.search-box input{
    width:420px;
    padding:15px;
    border:1px solid #ccc;
    border-radius:8px 0 0 8px;
    outline:none;
    font-size:16px;
}

.search-box button{
    padding:15px 30px;
    border:none;
    background:#2563EB;
    color:white;
    cursor:pointer;
    border-radius:0 8px 8px 0;
    font-size:16px;
}

.search-box button:hover{
    background:#1E40AF;
}

/* Events */

.events{
    width:90%;
    margin:40px auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:30px;
}

.event-card{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
    transition:.3s;
}

.event-card:hover{
    transform:translateY(-10px);
}

.event-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.content{
    padding:20px;
}

.category{
    display:inline-block;
    background:#DBEAFE;
    color:#2563EB;
    padding:6px 15px;
    border-radius:20px;
    font-size:13px;
    margin-bottom:15px;
}

.content h2{
    margin:15px 0;
    color:#1E3A8A;
}

.content p{
    margin:8px 0;
    color:#666;
}

.buttons{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-top:20px;
}

.details,
.register{
    width:48%;
    padding:12px;
    border-radius:8px;
    text-decoration:none;
    text-align:center;
    font-weight:bold;
}

.details{
    border:2px solid #2563EB;
    color:#2563EB;
}

.details:hover{
    background:#2563EB;
    color:white;
}

.register{
    background:#2563EB;
    color:white;
}

.register:hover{
    background:#1E40AF;
}

/* Footer */

footer{
    background:#1E3A8A;
    color:white;
    text-align:center;
    padding:20px;
    margin-top:50px;
}

/* Responsive */

@media(max-width:768px){

header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}

nav{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
}

nav a{
    margin:10px;
}

.search-box{
    flex-direction:column;
    align-items:center;
}

.search-box input{
    width:90%;
    border-radius:8px;
}

.search-box button{
    width:90%;
    border-radius:8px;
    margin-top:10px;
}

.hero h1{
    font-size:34px;
}

}

</style>
</head>
<body>

<!-- Header -->

<header>

<div class="logo">
<i class="fa-solid fa-calendar-days"></i>
SmartEvent
</div>

<nav>

<a href="index.php">Home</a>
<a href="event.php">Events</a>

<?php if ($is_logged_in): ?>
    <a href="myregistration.php">My Registration</a>
    <a href="profile.php">Profile</a>
    <a href="Logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="registration.php">Register</a>
<?php endif; ?>

</nav>

</header>

<!-- Hero -->

<section class="hero">

<h1>Explore Upcoming Events</h1>
<p>
Find events that match your interests and register to participate.
Register in just one click.
</p>

<div class="search-box">

<form action="event.php" method="GET" style="display:flex;">
<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Event">

<button type="submit"><i class="fa-solid fa-magnifying-glass"></i>
Search
</button>

</form>
</div>

</section>

<!-- Events -->

<section class="events">

<?php if (mysqli_num_rows($events_result) === 0): ?>

    <p style="grid-column:1/-1; text-align:center; color:#666;">No events found<?php echo !empty($search) ? ' for "' . htmlspecialchars($search) . '"' : ''; ?>.</p>

<?php else: ?>

    <?php while ($event = mysqli_fetch_assoc($events_result)): ?>

    <div class="event-card">

        <img src="<?php echo htmlspecialchars($event['image_url']); ?>">

        <div class="content">

            <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>

            <p>📅 <?php echo date("j F Y", strtotime($event['event_date'])); ?></p>
            <p>🕒 <?php echo date("g:i A", strtotime($event['event_time'])); ?></p>
            <p>📍 <?php echo htmlspecialchars($event['venue']); ?></p>
            <p>👥 Seats Left : <?php echo max((int) $event['seats_left'], 0); ?></p>

            <div class="buttons">

                <a href="eventdetail.php?id=<?php echo $event['id']; ?>" class="details">View Details</a>

                <?php if ($is_logged_in): ?>
                    <a href="registerevent.php?event_id=<?php echo $event['id']; ?>" class="register">Register Now</a>
                <?php else: ?>
                    <a href="login.php" class="register">Login to Register</a>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php endwhile; ?>

<?php endif; ?>

</section>


<!-- Footer -->

<!-- <footer>

<p>© 2026 EventHub | Event Registration System</p>

</footer> -->

</body>
</html>