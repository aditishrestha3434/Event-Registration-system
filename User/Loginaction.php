<?php
// Step 1: Start session and connect to database
session_start();
include '../config/db.php';

// Step 2: Get form values
$email = $_POST['email'];
$password = $_POST['password'];

// Step 3: Look up the user by email
$stmt = mysqli_prepare($conn, "SELECT id, full_name, password FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Step 4: Check the user exists and the password matches the hashed one we saved
if ($user && password_verify($password, $user['password'])) {

    // Step 5: Save their info in the session and send them to the events page
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    header("Location: event.php");
    exit();

} else {
    // Step 6: Wrong email or password
    header("Location: login.php?error=1");
    exit();
}
?>