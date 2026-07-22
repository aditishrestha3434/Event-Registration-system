<?php
// Step 1: Start session and require login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Step 2: Connect to database
<<<<<<< HEAD
include '../config/db.php';
=======
include 'config/db.php';
>>>>>>> a49a4951ba294234c7add2e85f8f402cbe966ed3

// Step 3: Fetch the logged-in user's data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
<title>My Profile - Event Registration System</title>
=======
<title>My Profile | EventHub</title>
>>>>>>> a49a4951ba294234c7add2e85f8f402cbe966ed3
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
    max-width:800px;
    margin:40px auto;
}

.wrapper h1{
    color:#1E3A8A;
    margin-bottom:20px;
}

.profile-card{
    display:flex;
    gap:40px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,.08);
}

.profile-image{
    width:200px;
    text-align:center;
}

.profile-image .avatar-circle{
    width:150px;
    height:150px;
    border-radius:50%;
    background:#2563EB;
    color:white;
    font-size:50px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px auto;
}

.profile-form{
    flex:1;
}

.profile-form label{
    display:block;
    margin-top:15px;
    margin-bottom:8px;
    font-weight:bold;
}

.profile-form input,
.profile-form textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}

.save-btn{
    margin-top:22px;
    padding:14px 25px;
    background:#22C55E;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

.save-btn:hover{
    background:#16A34A;
}

.alert{
    padding:14px;
    border-radius:8px;
    margin-bottom:20px;
}

.alert.success{ background:#dcfce7; color:#166534; }
.alert.error{ background:#fee2e2; color:#991b1b; }

{
header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}
.wrapper{
    width:92%;
}
.profile-card{
    flex-direction:column;
    align-items:center;
}
.profile-image{
    width:100%;
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

    <h1>My Profile</h1>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert success">Your profile was updated successfully.</div>
    <?php endif; ?>

    <div class="profile-card">

        <div class="profile-image">
            <div class="avatar-circle">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <p style="color:#666; font-size:13px;">Member since <?php echo date("M Y", strtotime($user['created_at'])); ?></p>
        </div>

        <div class="profile-form">
            <form action="profileaction.php" method="POST">

                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

                
                <label>Address</label>
                <textarea name="address" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>

                <button class="save-btn" type="submit"> Save Changes</button>

            </form>
        </div>

    </div>

</div>

</body>
</html>
<!-- <i class="fa-solid fa-floppy-disk"></i> -->