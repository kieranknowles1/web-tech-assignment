<?php
// Set $title before including

require_once "utility.php";
utility::noDirectAccess();

// Set by search.php
if (!isset($searchQuery)) {
    $searchQuery = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title?></title>
    <link href="/style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/scripts.js"></script>
    <?php
        //$extraScripts = ["/admin/scripts.js"];
        // https://www.php.net/manual/en/control-structures.foreach.php
        if (isset($extraScripts)) {
            foreach($extraScripts as $script) {
                echo "<script src='$script'></script>\n";
            }
        }
    ?>
</head>
<body>
<div class="navContainer">
    <header>
        <h1><a href="/">Leading Choice Getaways</a></h1>
        <a href="/" class="ham_button" onclick="ham_toggle('nav_menu'); return false">&#9776;</a>
    </header>
    <nav id="nav_menu">
        <form action="search.php" onsubmit="return !isEmpty('query')"> <!-- Silently fail if no input-->
            <input class="search" name="q" id="query" type="text" placeholder="Search" value="<?php echo $searchQuery ?>"> <!--TODO: Tab index-->
        </form>
        <a href="/">Home</a>
        <a href="/admin">Admin</a>
        <a href="/credits.php">Credits</a>
        <a href="/wireframes.php">Wireframes</a>
    </nav>
</div>