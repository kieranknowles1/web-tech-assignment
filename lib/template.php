<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    $title = "";

    require "$root/header.php";
?>
<?php
    require "$root/footer.php";
?>