<?php
// Create Connection
$host = "localhost";
$username = "root";
$password = "";
$database = "nepalbazar";

// Make Connectoin
$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>