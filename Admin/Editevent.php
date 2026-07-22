<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get event id from URL
$id = $_GET['id'];

// Step 4: Fetch the event details
$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

// Step 5: If no event found, go back to manage events
if (!$event) {
    header("Location: adminevents.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Event | EventHub Admin</title>
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
    width:90%;
    max-width:650px;
    margin:40px auto;
}

.wrapper h1{
    color:#1E3A8A;
    margin-bottom:20px;
}

form{
    background:white;
    border-radius:12px;
    box-shadow:0 10px 20px rgba(0,0,0,.06);
    padding:30px;
}

label{
    display:block;
    margin-top:16px;
    margin-bottom:8px;
    font-weight:600;
    font-size:14px;
}

input, textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    font-family:inherit;
}

.row{
    display:flex;
    gap:16px;
}

.row > div{
    flex:1;
}

button{
    margin-top:24px;
    width:100%;
    padding:15px;
    background:#1E3A8A;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#0f172a;
}

.back-link{
    display:inline-block;
    margin-bottom:16px;
    color:#1E3A8A;
    text-decoration:none;
    font-weight:600;
}

</style>
</head>
<body>

<header>
    <div class="logo"><i class="fa-solid fa-user-shield"></i> EventHub Admin</div>
    <nav>
        <a href="Admindashboard.php">Dashboard</a>
        <a href="Adminevents.php">Events</a>
        <a href="Adminregistrations.php">Registrations</a>
        <a href="Adminlogout.php">Logout</a>
    </nav>
</header>

<div class="wrapper">

    <a href="Adminevents.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Events</a>

    <h1>Edit Event</h1>

    <form action="editeventaction.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">

        <label>Event Name</label>
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>

        <div class="row">
            <div>
                <label>Event Date</label>
                <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
            </div>
            <div>
                <label>Event Time</label>
                <input type="time" name="event_time" value="<?php echo $event['event_time']; ?>" required>
            </div>
        </div>

        <label>Venue</label>
        <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>

        <label>Image URL</label>
        <input type="text" name="image_url" value="<?php echo htmlspecialchars($event['image_url']); ?>">

        <label>Description</label>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label>Capacity</label>
        <input type="number" name="capacity" min="1" value="<?php echo $event['capacity']; ?>" required>

        <button type="submit">Update Event</button>

    </form>

</div>

</body>
</html>