<?php
// Step 1: Start session and connect to database
session_start();
include 'config/db.php';

// Step 2: Get form values
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Step 3: Basic validation
if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
    header("Location: registration.php?error=1");
    exit();
}

// Step 4: Make sure both passwords match
if ($password !== $confirm_password) {
    header("Location: registration.php?error=mismatch");
    exit();
}

// Step 5: Check if the email is already registered
$check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    header("Location: registration.php?error=exists");
    exit();
}

// Step 6: Hash the password before saving it (never store plain-text passwords)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Step 7: Insert the new user
$stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $phone, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    // Step 8: Log the user in right away and send them to the events page
    $new_user_id = mysqli_insert_id($conn);
    $_SESSION['user_id'] = $new_user_id;
    $_SESSION['user_name'] = $full_name;
    header("Location: event.php");
    exit();
} else {
    header("Location: registration.php?error=1");
    exit();
}
?>