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
<title>Register | EventHub</title>

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

/* Split screen register */

.container{
display:flex;
min-height:calc(100vh - 82px);
}

/* Left */

.left{

width:45%;
background:linear-gradient(135deg,#2563EB,#1E3A8A);
display:flex;
justify-content:center;
align-items:center;
flex-direction:column;
color:white;
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

/* Right */

.right{

width:55%;
display:flex;
justify-content:center;
align-items:center;
background:white;

}

.register-box{

width:430px;

}

.register-box h2{

font-size:35px;
color:#1E3A8A;
margin-bottom:10px;

}

.register-box p{

margin-bottom:25px;
color:gray;

}

.input-group{

margin-bottom:18px;

}

.input-group label{

display:block;
margin-bottom:8px;
font-weight:bold;

}

.input-group input{

width:100%;
padding:13px;
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
top:15px;
cursor:pointer;
color:gray;

}

button{

width:100%;
padding:15px;
background:#2563EB;
color:white;
border:none;
border-radius:8px;
font-size:17px;
cursor:pointer;
transition:.3s;

}

button:hover{

background:#1E3A8A;

}

.login{

text-align:center;
margin-top:20px;

}

.login a{

color:#2563EB;
font-weight:bold;
text-decoration:none;

}

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
height:auto;
padding:400px 20px;

}

.register-box{

width:95%;

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

<div class="right">

<div class="register-box">

<h2>Create Account</h2>
<p>Register to continue</p>

<?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
    <p style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px;">
        An account with that email already exists. Try logging in instead.
    </p>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'mismatch'): ?>
    <p style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px;">
        Passwords do not match. Please try again.
    </p>
<?php elseif (isset($_GET['error'])): ?>
    <p style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px;">
        Please fill in all fields correctly.
    </p>
<?php endif; ?>

<form action="signupaction.php" method="POST">

<div class="input-group">

<label>Full Name</label>
<input type="text"name="full_name"placeholder="Enter full name"required>

</div>

<div class="input-group">

<label>Email</label>
<input type="email"name="email"placeholder="Enter email"required>

</div>

<div class="input-group">
<label>Phone Number</label>
<input type="text"name="phone"placeholder="Enter phone number"required>

</div>

<div class="input-group">

<label>Password</label>

<div class="password">

<input type="password"
id="pass"name="password"placeholder="Enter password"required>

<i class="fa-solid fa-eye"
onclick="showPass('pass')"></i>

</div>

</div>

<div class="input-group">

<label>Confirm Password</label>

<div class="password">
<input type="password"id="cpass"name="confirm_password"placeholder="Confirm password"required>

<i class="fa-solid fa-eye"
onclick="showPass('cpass')"></i>

</div>

</div>

<button type="submit">
Create Account
</button>

</form>

<div class="login">
Already have an account?
<a href="login.php">
Login
</a>

</div>

</div>

</div>

</div>

<script>

function showPass(id){

var x=document.getElementById(id);

if(x.type==="password"){

x.type="text";

}else{

x.type="password";

}

}

</script>

</body>
</html>