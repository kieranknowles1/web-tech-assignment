<?php
// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    http_response_code(403);
    exit("Direct access not permitted.");
}
?>



<body>
<div class="navContainer">
    <script src="ham_menu.js"></script>
    <header>
        <h1><a href="/">Leading Choice Getaways</a></h1>
        <a href="/" class="ham_button" onclick="ham_toggle('nav_menu'); return false">&#9776;</a>
    </header>
    <nav id="nav_menu">
        <a href="holidays.php">Browse Holidays</a>
    </nav>
</div>