<?php
// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    header('HTTP/1.0 403 Forbidden');
    exit("Direct access not permitted.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Leading Choice Getaways</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="ham_menu.js"></script>
</head>

<body>
<div class="gridContainer">
    <header>
        <h1><a href="">Leading Choice Getaways</a></h1>
        <a href="" class="ham_button" onclick="ham_toggle('nav_menu'); return false">&#9776;</a>
    </header>
    <nav id="nav_menu">
        <a href="holidays.php">Browse Holidays</a>
    </nav>
</div>