<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    $title = "";

    require "$root/lib/header.php";
?>
<?php
    require "$root/lib/footer.php";
?>