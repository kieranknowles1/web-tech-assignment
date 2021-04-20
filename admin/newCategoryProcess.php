<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    $title = "New Category Submit";

    require "$root/lib/header.php";
    require "$root/lib/database_conn.php";

    $desc = $conn->real_escape_string(utility::tryGet("desc", true));

    // Validate image
    // https://www.w3schools.com/php/php_file_upload.asp
    $tmpName = $_FILES["image"]["tmp_name"];
    echo "<p>$tmpName</p>";

    echo "<p>Description: $desc</p>";


    $check = getimagesize($tmpName);
    if ($check !== false) {
        echo "File is an image.";
    }
    else {
        echo "<p>File is not an image</p>";
        exit(1);
    }

    $mimeType = $check["mime"];
    if ($mimeType != "image/jpeg") {
        echo "<p>Expected image/jpeg but got $mimeType";
    }

    if ($_FILES["image"]["size"] > 500000) {
        echo "<p>Image is too large</p>";
    }

    // Check for duplicate category
    $sql = "SELECT null
            FROM LCG_category
            WHERE lower(catDesc) = lower('$desc')";
    $queryResult = utility::query($conn, $sql);

    if ($queryResult->num_rows != 0) {
        echo "<p>There is already a category named $desc</p>";
        exit(1);
    }

    // Generate ID
    // Database uses string for ID so this is necessary
    // https://stackoverflow.com/questions/6296822/how-to-format-numbers-with-00-prefixes-in-php
    $id = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    echo "<p>ID: $id</p>";

    // Add to database
    $sql = "INSERT INTO LCG_category (catID, catDesc)
            VALUES ('$id', '$desc');";
    $queryResult = utility::query($conn, $sql);

    // Upload image
    $targetFile = "$root/images/category/$id.jpg";
    echo "<p>Image file: $targetFile</p>";

    move_uploaded_file($tmpName, $targetFile);

    require "$root/lib/footer.php";
?>