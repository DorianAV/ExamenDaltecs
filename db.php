<?php
$servername = "localhost";
$username = "root";
$password = "2004";
$dbname = "foodsaver";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
