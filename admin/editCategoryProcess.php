<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once "$root/lib/database_conn.php";
    require_once "$root/lib/utility.php";

    $desc = $conn->real_escape_string(utility::tryGet("desc", true));

    $catID = $conn->real_escape_string(utility::tryGet("id"));

    $deleting = false;
    if ($catID == null) {
        $isNew = true;
        $title = "New Category Submit";
    }
    else {
        $isNew = false;
        $title = "Update category submit";

        // Check that ID is valud
        $sql = "SELECT null
        FROM LCG_category
        WHERE catID = '$catID'
        LIMIT 1";
        $queryResult = utility::query($conn, $sql);
        if ($queryResult->num_rows == 0) {
            echo "<p>Invalid category id $catID</p>";
            exit(1);
        }

        // Are we deleting?
        // https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form
        if (utility::tryGet("action", true) == "Delete") {
            $title = "Delete category";
            $deleting = true;
        }
    }
    
    require "$root/lib/header.php";

    if ($deleting) {
        // Check that the category is unused
        $sql = "SELECT null FROM LCG_holidays
                WHERE catID = '$catID'";
        $queryResult = utility::query($conn, $sql);

        if ($queryResult->num_rows != 0) {
            echo "<p>Could not delete category '$desc' as it is in use by $queryResult->num_rows holidays</p>";
            exit(1);
        }
        else {
            echo "<p>Deleting category $catID</p>";
        }

        $sql = "DELETE FROM LCG_category
                WHERE catID = '$catID'
                LIMIT 1"; // Safety
        $queryResult = utility::query($conn, $sql);

        $imgFile = "$root/images/category/$catID.jpg";
        echo "<p>Image: $imgFile</p>";
        unlink($imgFile);
    }
    else {
        // Validate image
        // https://www.w3schools.com/php/php_file_upload.asp
        $tmpName = $_FILES["image"]["tmp_name"];
        if ($tmpName != null) {
            $doImg = true;
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
        }
        else {
            if ($isNew) {
                echo "<p>Expected an image</p>";
                exit(1);
            }
            else {
                $doImg = false;
            }
        }

        if ($isNew) {
            // Check for duplicate category
            $sql = "SELECT null
                    FROM LCG_category
                    WHERE lower(catDesc) = lower('$desc')";
            $queryResult = utility::query($conn, $sql);

            if ($queryResult->num_rows != 0) {
                echo "<p>There is already a category named $desc</p>";
                exit(1);
            }
            echo "<p>Adding to database</p>";
            // Generate ID
            // Database uses string for ID so this is necessary
            // https://stackoverflow.com/questions/6296822/how-to-format-numbers-with-00-prefixes-in-php
            $catID = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            echo "<p>ID: $catID</p>";

            // Add to database
            $sql = "INSERT INTO LCG_category (catID, catDesc)
                    VALUES ('$catID', '$desc');";
            $queryResult = utility::query($conn, $sql);
        }
        else {
            // Update existing
            echo "<p>Updating existing category id=$catID</p>";
            $sql = "UPDATE LCG_category
                    SET catDesc = '$desc'
                    WHERE catID = '$catID'";
            $queryResult = utility::query($conn, $sql);
            
        }

        // Upload image if necessary
        if ($doImg) {
            echo "<p>Uploading image</p>";
            $targetFile = "$root/images/category/$catID.jpg";
            echo "<p>Image file: $targetFile</p>";

            move_uploaded_file($tmpName, $targetFile);
        }
    }

    require "$root/lib/footer.php";
?>