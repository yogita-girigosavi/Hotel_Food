<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hotel_food_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
