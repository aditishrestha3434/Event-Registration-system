<?php
// Step 1: Start session and destroy it
session_start();
session_destroy();

// Step 2: Redirect to homepage
header("Location: index.php");
exit();
?>