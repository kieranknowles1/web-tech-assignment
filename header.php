<?php
// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    http_response_code(403);
    exit("Direct access not permitted.");
}

// Set by search.php
if (!isset($searchQuery)) {
    $searchQuery = "";
}
?>



<body>
<div class="navContainer">
    <script src="scripts.js"></script>
    <header>
        <h1><a href="/">Leading Choice Getaways</a></h1>
        <a href="/" class="ham_button" onclick="ham_toggle('nav_menu'); return false">&#9776;</a>
    </header>
    <nav id="nav_menu">
        <form action="search.php" onsubmit="return !isEmpty('query')"> <!-- Silently fail if no input-->
            <input class="search" name="q" id="query" type="text" placeholder="Search" value="<?php echo $searchQuery ?>"> <!--TODO: Tab index-->
        </form>
        <a href="holidays.php">Browse Holidays</a>
    </nav>
</div>