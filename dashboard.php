<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

echo "<h1>Welcome to your Dashboard, " . $_SESSION['user'] . "!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>
