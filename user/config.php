<?php
$host = "localhost";
$user = "root";      // default for XAMPP
$pass = "";          // leave empty unless you set a password in MySQL
$db   = "funded_db"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
