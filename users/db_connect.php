<?php
$host = "localhost";  // Change to your database host if necessary
$user = "root";       // Change to your database username
$pass = "";           // Change to your database password
$db_name = "Aeady salon";  // Change to your database name

$conn = new mysqli($host, $user, $pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
