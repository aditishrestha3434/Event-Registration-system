<?php
// Step 1: Check admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Adminlogin.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get event id from URL
$id = $_GET['id'];

// Step 4: Delete the event (registrations for this event are deleted automatically
// because of ON DELETE CASCADE in the database)
$stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Step 5: Redirect back to manage events
header("Location: adminevents.php?deleted=1");
exit();
?>