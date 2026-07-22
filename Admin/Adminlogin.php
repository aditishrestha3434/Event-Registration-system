<?php
// Step 1: Start session and check if already logged in
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admindashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | EventHub</title>
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
}

.container{
    display:flex;
    min-height:100vh;
}

.left{
    width:50%;
    background:linear-gradient(135deg,#1E3A8A,#0f172a);
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    padding:40px;
    text-align:center;
}

.left i{
    font-size:90px;
    margin-bottom:20px;
}

.left h1{
    font-size:40px;
    margin-bottom:15px;
}

.left p{
    font-size:18px;
    line-height:30px;
}

.right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:white;
}

.login-box{
    width:400px;
}

.login-box h2{
    font-size:32px;
    color:#1E3A8A;
    margin-bottom:10px;
}

.login-box p{
    color:gray;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

.input-group input{
    width:100%;
    padding:14px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}

.login-btn{
    width:100%;
    padding:15px;
    border:none;
    background:#1E3A8A;
    color:white;
    font-size:17px;
    border-radius:8px;
    cursor:pointer;
}

.login-btn:hover{
    background:#0f172a;
}

.error-box{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:8px;
    margin-bottom:18px;
}

.hint{
    margin-top:20px;
    font-size:13px;
    color:#999;
    text-align:center;
}

@media(max-width:900px){
.container{
    flex-direction:column;
}
.left, .right{
    width:100%;
    height:auto;
    padding:40px 20px;
}
.login-box{
    width:100%;
    max-width:400px;
}
}

</style>
</head>
<body>

<div class="container">

    <div class="left">
        <i class="fa-solid fa-user-shield"></i>
        <h1>Admin Panel</h1>
        <p>Manage events, track registrations, and keep EventHub running smoothly.</p>
    </div>

    <div class="right">
        <div class="login-box">

            <h2>Admin Login</h2>
            <p>Sign in to manage the site</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-box">Invalid username or password.</div>
            <?php endif; ?>

            <form action="Adminloginaction.php" method="POST">

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button class="login-btn" type="submit">Login</button>

            </form>

          <!-- //  Default credentials: admin / admin123</p> -->

        </div>
    </div>

</div>

</body>
</html>