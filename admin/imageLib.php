<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/utility.php";
utility::noDirectAccess();

function checkForImage($name, $required) {
// Validate image
// https://www.w3schools.com/php/php_file_upload.asp
    $tmpName = $_FILES[$name]["tmp_name"];
    if ($tmpName != null) {
        echo "<p>$tmpName</p>";


        $check = getimagesize($tmpName);
        if ($check !== false) {
            echo "File is an image.";
        }
        else {
            utility::cleanExit("File is not an image", 400);
        }

        $mimeType = $check["mime"];
        if ($mimeType != "image/jpeg") {
            utility::cleanExit("Expected image/jpeg but got $mimeType", 400);
        }

        if ($_FILES["image"]["size"] > 500000) {
            utility::cleanExit("Image is too large", 400);
        }

        return $tmpName;
    }
    else {
        if ($required) {
            utility::cleanExit("Expected an image", 400);
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