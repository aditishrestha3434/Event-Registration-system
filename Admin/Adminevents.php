<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Fetch all events with a live count of how many people registered
$sql = "SELECT events.*, COUNT(registrations.id) AS registered_count
        FROM events
        LEFT JOIN registrations ON registrations.event_id = events.id
        GROUP BY events.id
        ORDER BY events.event_date ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Events | EventHub Admin</title>
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
    max-width:1200px;
    margin:40px auto;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.page-header h1{
    color:#1E3A8A;
}

.add-btn{
    background:#1E3A8A;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
}

.add-btn:hover{
    background:#0f172a;
}

.alert{
    padding:14px;
    border-radius:8px;
    margin-bottom:20px;
}

.alert.success{ background:#dcfce7; color:#166534; }

.panel{
    background:white;
    border-radius:12px;
    box-shadow:0 10px 20px rgba(0,0,0,.06);
    padding:10px 25px;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:14px;
    text-align:left;
    border-bottom:1px solid #eee;
    white-space:nowrap;
}

th{
    color:#999;
    font-size:12px;
    text-transform:uppercase;
}

.badge{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge.upcoming{ background:#DBEAFE; color:#1E3A8A; }
.badge.past{ background:#f1f5f9; color:#64748b; }

.action-icons a{
    display:inline-block;
    width:32px;
    height:32px;
    line-height:32px;
    text-align:center;
    border-radius:7px;
    margin-right:6px;
    text-decoration:none;
}

.action-icons .edit{ background:#DBEAFE; color:#2563EB; }
.action-icons .delete{ background:#fee2e2; color:#ef4444; }

.empty{
    text-align:center;
    color:#999;
    padding:40px;
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

    <div class="page-header">
        <h1>Manage Events</h1>
        <a href="Addevent.php" class="add-btn"><i class="fa-solid fa-plus"></i> Add Event</a>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert success">Event added successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert success">Event updated successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert success">Event deleted successfully.</div>
    <?php endif; ?>

    <div class="panel">

        <?php if (mysqli_num_rows($result) === 0): ?>

            <div class="empty">No events yet. Click "Add Event" to create your first one.</div>

        <?php else: ?>

            <table>
                <tr>
                    <th>Event Name</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $is_upcoming = strtotime($row['event_date']) >= strtotime(date('Y-m-d')); ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['event_name']); ?></strong></td>
                    <td><?php echo date("M j, Y", strtotime($row['event_date'])); ?> &middot; <?php echo date("g:i A", strtotime($row['event_time'])); ?></td>
                    <td><?php echo htmlspecialchars($row['venue']); ?></td>
                    <td><?php echo $row['registered_count']; ?> / <?php echo $row['capacity']; ?></td>
                    <td>
                        <?php if ($is_upcoming): ?>
                            <span class="badge upcoming">Upcoming</span>
                        <?php else: ?>
                            <span class="badge past">Past</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-icons">
                        <a href="Editevent.php?id=<?php echo $row['id']; ?>" class="edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="deleteevent.php?id=<?php echo $row['id']; ?>" class="delete" title="Delete" onclick="return confirm('Delete this event? This will also remove its registrations.');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php endif; ?>

    </div>

</div>

</body>
</html>