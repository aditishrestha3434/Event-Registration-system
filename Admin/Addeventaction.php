<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get form values
$event_name = $_POST['event_name'];
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$venue = $_POST['venue'];
$image_url = trim($_POST['image_url']);
$description = $_POST['description'];
$capacity = $_POST['capacity'];

// Step 4: Basic validation
if (empty($event_name) || empty($event_date) || empty($event_time) || empty($venue) || empty($capacity)) {
    header("Location: Addevent.php?error=1");
    exit();
}

// Step 5: If no image was given, use a default placeholder
if (empty($image_url)) {
    $image_url = "https://picsum.photos/400/220?" . rand(1, 1000);
}

// Step 6: Use a prepared statement to safely insert the data
$stmt = mysqli_prepare($conn, "INSERT INTO events (event_name, event_date, event_time, venue, description, capacity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssssis", $event_name, $event_date, $event_time, $venue, $description, $capacity, $image_url);

// Step 7: Execute and redirect
if (mysqli_stmt_execute($stmt)) {
    header("Location: Adminevents.php?added=1");
    exit();
} else {
    header("Location: Addevent.php?error=1");
    exit();
}
?>