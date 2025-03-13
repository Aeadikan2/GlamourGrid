<?php
$con = mysqli_connect("localhost", "root", "", "aeady salon"); // ✅ Fixed database name

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error()); // ✅ Show proper error if connection fails
}
?>
