<?php
$servername = "localhost";
$username = "u914188159_mat";
$password = "XXXXXXXXXXXX";
$dbname = "u914188159_stock";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>
