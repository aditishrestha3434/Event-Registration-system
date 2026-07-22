<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get form values
$id = $_POST['id'];
$event_name = $_POST['event_name'];
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$venue = $_POST['venue'];
$image_url = $_POST['image_url'];
$description = $_POST['description'];
$capacity = $_POST['capacity'];

// Step 4: Use a prepared statement to safely update the data
$stmt = mysqli_prepare($conn, "UPDATE events SET event_name=?, event_date=?, event_time=?, venue=?, image_url=?, description=?, capacity=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "ssssssii", $event_name, $event_date, $event_time, $venue, $image_url, $description, $capacity, $id);

// Step 5: Execute and redirect
if (mysqli_stmt_execute($stmt)) {
    header("Location: adminevents.php?updated=1");
    exit();
} else {
    header("Location: editevent.php?id=" . $id . "&error=1");
    exit();
}
?>