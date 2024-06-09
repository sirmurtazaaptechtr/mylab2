<?php
include('./include/functions.php');

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "mylab2_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "$database Connected successfully";
?>