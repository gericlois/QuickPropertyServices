<?php
// specify the server, username, password, and database to connect to
$server = "localhost";
$username = "root";
$password = "";
$database = "quickproperty";

// create a new MySQLi object and connect to the database
$conn  = new mysqli($server, $username, $password, $database);

// check if there is an error connecting to the database
if ($conn ->connect_error) {
    die("Error: Could not connect to database. " . $conn ->connect_error);
}
// success message
echo "";
?>