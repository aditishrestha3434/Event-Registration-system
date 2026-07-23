
<?php
// Step 1: Start session and require login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Step 2: Connect to database
include '../config/db.php';

// Step 3: Get form values
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Step 4: Update the user's row
$stmt = mysqli_prepare($conn, "UPDATE users SET full_name=?, email=?, phone=?, address=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $email, $phone, $address, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

// Step 5: Update the session name too, so the header greeting stays correct
$_SESSION['user_name'] = $full_name;

header("Location: profile.php?updated=1");
exit();
?>