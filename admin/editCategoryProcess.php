<?php
    // https://css-tricks.com/php-include-from-root/
    global $root;
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once "$root/lib/database_conn.php";
    require_once "$root/lib/utility.php";

    require_once "imageLib.php";

    $desc = $conn->real_escape_string(utility::tryGet("desc", true));

    $catID = $conn->real_escape_string(utility::tryGet("id"));

    if ($catID == null) {
        $title = "New Category Submit";
        require "$root/lib/header.php";

        newCategory($desc);
    }
    else {
        $title = "Update category submit";

        // Check that ID is valud
        $sql = "SELECT null
        FROM LCG_category
        WHERE catID = '$catID'
        LIMIT 1";
        $queryResult = utility::query($sql);
        if ($queryResult->num_rows == 0) {
            echo "<p>Invalid category id $catID</p>";
            exit(1);
        }

        // Are we deleting?
        // https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form
        $deleting = utility::tryGet("action", true) == "Delete";

        if ($deleting) {
            $title = "Delete Category";
            require "$root/lib/header.php";
            deleteCategory($catID);
        }
        else {
            $title = "Edit Category";
            require "$root/lib/header.php";
            updateCategory($catID, $desc);
        }
    }

    require "$root/lib/footer.php";

    function newCategory($desc) {
        global $root;

        // Generate ID
        // Database uses string for ID so this is necessary
        // https://stackoverflow.com/questions/6296822/how-to-format-numbers-with-00-prefixes-in-php
        $catID = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        echo "<p>ID: $catID</p>";

        // Check that an image was uploaded
        $tmpName = checkForImage(true);

        echo "<p>Description: $desc</p>";

        // Check for duplicate category
        $sql = "SELECT null
                FROM LCG_category
                WHERE lower(catDesc) = lower('$desc')";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows != 0) {
            echo "<p>There is already a category named $desc</p>";
            exit(1);
        }
        echo "<p>Adding to database</p>";

        // Actually upload the image
        if ($tmpName != null) {
            $targetFile = "$root/images/category/$catID.jpg";
            uploadImage($tmpName, $targetFile);
        }

        // Add to database
        $sql = "INSERT INTO LCG_category (catID, catDesc)
                VALUES ('$catID', '$desc');";
        $queryResult = utility::query($sql);
    }

    function updateCategory($id, $desc) {
        global $conn;

        // Update existing
        echo "<p>Updating existing category id=$id</p>";
        $sql = "UPDATE LCG_category
                SET catDesc = '$desc'
                WHERE catID = '$id'";
        $queryResult = utility::query($sql);

        // Upload image if provided
        $imgName = checkForImage(false);
        if ($imgName != null) {
            uploadImage($imgName);
        }
    }

    function deleteCategory($id) {
        global $conn, $root;

        // Check that the category is unused
        $sql = "SELECT null FROM LCG_holidays
                WHERE catID = '$id'";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows != 0) {
            echo "<p>Could not delete category '$desc' as it is in use by $queryResult->num_rows holidays</p>";
            exit(1);
        }
        echo "<p>Deleting category $id</p>";

        $sql = "DELETE FROM LCG_category
                WHERE catID = '$id'
                LIMIT 1"; // Safety
        $queryResult = utility::query($sql);

        $imgFile = "$root/images/category/$id.jpg";
        echo "<p>Image: $imgFile</p>";
        unlink($imgFile);
    }
?>