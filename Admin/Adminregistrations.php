<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Fetch all registrations, joined with user and event details
$sql = "SELECT registrations.id, registrations.status, registrations.registered_at, registrations.message,
               users.full_name, users.email, users.phone,
               events.event_name, events.event_date
        FROM registrations
        JOIN users ON registrations.user_id = users.id
        JOIN events ON registrations.event_id = events.id
        ORDER BY registrations.registered_at DESC";
$result = mysqli_query($conn, $sql);
?>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrations | EventHub Admin</title>
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
    width:92%;
    max-width:1200px;
    margin:40px auto;
}

.wrapper h1{
    color:#1E3A8A;
    margin-bottom:6px;
}

.wrapper .subtitle{
    color:#666;
    margin-bottom:22px;
}

.search-box{
    margin-bottom:20px;
    max-width:400px;
}

.search-box input{
    width:100%;
    padding:12px 15px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:14px;
}

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
    color:white;
}

.badge.confirmed{ background:#22C55E; }
.badge.pending{ background:#F59E0B; }

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

    <h1>All Registrations</h1>
    <p class="subtitle">Everyone who has registered, across every event.</p>

    <div class="search-box">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by name, email, or event...">
    </div>

    <div class="panel">

        <?php if (mysqli_num_rows($result) === 0): ?>

            <div class="empty">No registrations yet.</div>

        <?php else: ?>

            <table id="regTable">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Event</th>
                    <th>Registered On</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                    <td><?php echo date("M j, Y", strtotime($row['registered_at'])); ?></td>
                    <td>
                        <?php if ($row['status'] === 'Confirmed'): ?>
                            <span class="badge confirmed">Confirmed</span>
                        <?php else: ?>
                            <span class="badge pending">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php endif; ?>

    </div>

</div>

<script>
// Step 4: Simple search box that filters the table rows as you type
function filterTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("regTable");
    if (!table) return;
    var rows = table.getElementsByTagName("tr");

    for (var i = 1; i < rows.length; i++) {
        var rowText = rows[i].textContent.toLowerCase();
        rows[i].style.display = rowText.indexOf(filter) > -1 ? "" : "none";
    }
}
</script>

</body>
</html>