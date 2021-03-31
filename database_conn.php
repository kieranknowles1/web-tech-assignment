<?php
// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    header('HTTP/1.0 403 Forbidden');
    exit("Direct access not permitted.");
}

// Connection can be accessed from $conn

// MySQLi parameters
$servername = "localhost";
$username = "unn_w20013000";
$password = "********";
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
