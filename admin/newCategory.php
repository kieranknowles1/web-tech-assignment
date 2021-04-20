<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    $title = "New Category";

    require "$root/lib/header.php";

    // https://www.w3schools.com/php/php_file_upload.asp
?>
<form method="post" action="newCategoryProcess.php" enctype="multipart/form-data">
    <label>Description: 
        <input type="text" name="desc" required></input>
    </label><br>

    <label>Image (max 500kb): 
        <input type="file" name="image" accept="image/jpeg" required>
    </label><br>

    <input type="submit" value="Submit">
</form>
<?php
    require "$root/lib/footer.php";
?>