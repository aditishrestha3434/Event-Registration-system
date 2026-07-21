<?php
// Step 1: Start the session so the header knows whether someone is logged in
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | EventHub</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f7fb;
}

/* Site header - same on every page */

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

header nav a{
    color:white;
    text-decoration:none;
    margin-left:25px;
    font-size:16px;
    transition:.3s;
}

header nav a:hover{
    color:#FFD700;
}

/* Split screen login */

.container{
    display:flex;
    min-height:calc(100vh - 82px);
}

/* Left Side */

.left{
    width:50%;
    background:linear-gradient(135deg,#2563EB,#1E3A8A);
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
    font-size:45px;
    margin-bottom:15px;
}

.left p{
    font-size:18px;
    line-height:30px;
}

/* Right Side */

.right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:white;
}

.login-box{
    width:420px;
}

.login-box h2{
    font-size:35px;
    color:#1E3A8A;
    margin-bottom:10px;
}

.login-box p{
    color:gray;
    margin-bottom:30px;
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

.password{
    position:relative;
}

.password i{
    position:absolute;
    right:15px;
    top:16px;
    cursor:pointer;
    color:gray;
}

.options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    font-size:14px;
}

.options a{
    text-decoration:none;
    color:#2563EB;
}

.login-btn{
    width:100%;
    padding:15px;
    border:none;
    background:#2563EB;
    color:white;
    font-size:17px;
    border-radius:8px;
    cursor:pointer;
    transition:.3s;
}

.login-btn:hover{
    background:#1E3A8A;
}

.register{
    text-align:center;
    margin-top:25px;
}

.register a{
    color:#2563EB;
    text-decoration:none;
    font-weight:bold;
}

/* Responsive */

@media(max-width:900px){

header{
    flex-direction:column;
    gap:15px;
    padding:20px;
}

.container{

    flex-direction:column;
    min-height:auto;
}

.left,
.right{
    width:100%;
    height:50vh;
}

.login-box{
    width:90%;
    justify-content:center;
}

.left h1{
    font-size:35px;
}

}

</style>

</head>
<body>

<!-- Site header - identical to index.php / event.php -->

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

<div class="container">



<!-- <div class="right"> -->

<div class="login-box">

<h2>Login</h2>

<p>Sign in to continue</p>

<?php if (isset($_GET['error'])): ?>
    <p style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px;">
        Invalid email or password. Please try again.
    </p>
<?php endif; ?>

<form action="Loginaction.php" method="POST">

<div class="input-group">

<label>Email Address</label>

<input type="email"
name="email"
placeholder="Enter your email"
required>

</div>

<div class="input-group">

<label>Password</label>

<div class="password">

<input type="password"
id="password"
name="password"
placeholder="Enter password"
required>

<i class="fa-solid fa-eye"
onclick="showPassword()"></i>

</div>

</div>

<div class="options">

<label>

<input type="checkbox">

Remember Me

</label>

<a href="#">Forgot Password?</a>

</div>

<button class="login-btn" type="submit">

Login

</button>

</form>

<div class="register">

Don't have an account?

<a href="registration.php">

Register

</a>

</div>

</div>

</div>

</div>

<script>

function showPassword(){

var x=document.getElementById("password");

if(x.type==="password"){

x.type="text";

}else{

x.type="password";

}

}

</script>

</body>
</html>