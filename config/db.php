<?php
// Step 1: Database connection settings
// Change these if your MySQL username/password is different
$host = "localhost";
$db_username = "root";
$db_password = "";
$database = "eventhub_db";

// Step 2: Create the connection
$conn = mysqli_connect($host, $db_username, $db_password, $database);

// Step 3: Check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
