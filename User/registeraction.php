<?php
// Step 1: Start session and check the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Step 2: Connect to database
include 'config/db.php';

// Step 3: Get form values
$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$message = $_POST['message'];

// Step 4: Check the event still has seats left (in case someone else filled them since the page loaded)
$stmt = mysqli_prepare($conn, "SELECT capacity, (SELECT COUNT(*) FROM registrations WHERE event_id = ?) AS taken FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ii", $event_id, $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$event || $event['taken'] >= $event['capacity']) {
    header("Location: eventdetail.php?id=" . $event_id . "&full=1");
    exit();
}

// Step 5: Insert the registration (the database itself blocks duplicate registrations
// thanks to the UNIQUE KEY we added on user_id + event_id)
$insert_stmt = mysqli_prepare($conn, "INSERT INTO registrations (user_id, event_id, message) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($insert_stmt, "iis", $user_id, $event_id, $message);

if (mysqli_stmt_execute($insert_stmt)) {
    header("Location:myregistration.php?registered=1");
    exit();
} else {
    header("Location: eventdetail.php?id=" . $event_id . "&error=1");
    exit();
}
?>