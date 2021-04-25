<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/utility.php";
utility::noDirectAccess();

function checkForImage($required) {
// Validate image
// https://www.w3schools.com/php/php_file_upload.asp
    $tmpName = $_FILES["image"]["tmp_name"];
    if ($tmpName != null) {
        echo "<p>$tmpName</p>";


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
            exit(1);
        }

        if ($_FILES["image"]["size"] > 500000) {
            echo "<p>Image is too large</p>";
            exit(1);
        }

        return $tmpName;
    }
    else {
        if ($required) {
            echo "<p>Expected an image</p>";
            exit(1);
        }
        else return null;
    }
}

function uploadImage($src, $dest) {
    echo "<p>Uploading image</p>";
    echo "<p>Image file: $dest</p>";

    move_uploaded_file($src, $dest);
}
?>