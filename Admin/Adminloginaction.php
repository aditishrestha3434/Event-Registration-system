<?php
// Step 1: Start session
session_start();

// Step 2: Get form values
$username = $_POST['username'];
$password = $_POST['password'];

// Step 3: Check against hardcoded admin credentials
// (For a bigger project, you would check these against an "admins" table in MySQL)
if ($username === "admin" && $password === "admin123") {

    // Step 4: Set session and redirect to dashboard
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    header("Location: Admindashboard.php");
    exit();

} else {
    // Step 5: Wrong credentials, go back to login with error
    header("Location: Adminlogin.php?error=1");
    exit();
}
?>