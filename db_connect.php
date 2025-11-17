<?php
// db_connect.php
$servername = "localhost";
$username = "u860705769_safari_user"; //  DB Username
$password = "Safari@456789"; //  DB Password
$dbname = "u860705769_safari_db";     //  DB Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>