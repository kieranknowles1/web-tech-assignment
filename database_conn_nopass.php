<?php
require_once "utility.php";
utility::noDirectAccess();

// Connection can be accessed from $conn

// MySQLi parameters
$servername = "localhost";
$username = "unn_w20013000";
$password = "********"; // oops, this is why you use password managers
$dbname = "unn_w20013000";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_errno) {
    $error = $conn->connect_error;
    echo "<p>Connection to database failed</p>";
    echo "<p>The error was: $error.</p>";
    exit;
}
?>