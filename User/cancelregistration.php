<?php
// Step 1: Start session and require login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

// Step 2: Connect to database
<<<<<<< HEADj
include '../config/db.php';
=======
include 'config/db.php';
>>>>>>> a49a4951ba294234c7add2e85f8f402cbe966ed3

// Step 3: Get registration id from URL
$id = $_GET['id'];

// Step 4: Only delete it if it belongs to the logged-in user
// (so someone can't cancel another person's registration by guessing the id)
$stmt = mysqli_prepare($conn, "DELETE FROM registrations WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

// Step 5: Redirect back to My Registrations
header("Location: myregistration.php?cancelled=1");
exit();
?>