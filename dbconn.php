<?php


$host = "localhost";
$username = "root"; // default for XAMPP
$password = "";     // default for XAMPP
$dbname = "campusbite";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>