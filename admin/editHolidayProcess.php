<?php

    class HolidayDetails {
        public $title;
        public $description;
        public $duration;
        public $locID;
        public $catID;
        public $price;
    }

    // https://css-tricks.com/php-include-from-root/
    global $root;
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once "$root/lib/database_conn.php";
    require_once "$root/lib/utility.php";

    require_once "imageLib.php";

    //print_r($_REQUEST);
    
    $details = new HolidayDetails;

    $details->title = $conn->real_escape_string(utility::tryGet("title", true));
    $details->description = $conn->real_escape_string(utility::tryGet("description", true));
    $details->duration = $conn->real_escape_string(utility::tryGet("duration", true));
    $details->locID = $conn->real_escape_string(utility::tryGet("locID", true));
    $details->catID = $conn->real_escape_string(utility::tryGet("catID", true));
    $details->price = $conn->real_escape_string(utility::tryGet("price", true));

    $holID = $conn->real_escape_string(utility::tryGet("id"));

    if ($holID == null) {
        $title = "New Holiday Submit";
        require "$root/lib/header.php";

        newHoliday($details);
    }
    else {
        $title = "Update Holiday submit";

        // Check that ID is valud
        $sql = "SELECT null
        FROM LCG_holidays
        WHERE holidayID = '$holID'
        LIMIT 1";
        $queryResult = utility::query($sql);
        if ($queryResult->num_rows == 0) {
            echo "<p>Invalid category id $holID</p>";
            exit(1);
        }

        // Are we deleting?
        // https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form
        $deleting = utility::tryGet("action", true) == "Delete";

        if ($deleting) {
            $title = "Delete Holiday";
            require "$root/lib/header.php";
            deleteHoliday($holID);
        }
        else {
            $title = "Edit Holiday";
            require "$root/lib/header.php";
            updateHoliday($holID, $desc);
        }
    }

    require "$root/lib/footer.php";

    function newHoliday($details) {
        global $root, $conn;

        // Not necessary as LCG_holidays uses the correct type for the ID
        // https://stackoverflow.com/questions/6296822/how-to-format-numbers-with-00-prefixes-in-php
        //$catID = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        //echo "<p>ID: $catID</p>";

        $tmpName = checkForImage(true);
        /*
        public $title;
        public $description;
        public $duration;
        public $locID;
        public $catID;
        public $price;
        */


        echo "<p>Title: $details->title</p>";
        echo "<p>Description: $details->description</p>";
        echo "<p>Duration: $details->duration</p>";
        echo "<p>locID: $details->locID</p>";
        echo "<p>catID: $details->catID</p>";
        echo "<p>Price: $details->price</p>";

        // Check for duplicate
        $sql = "SELECT NULL
                FROM LCG_holidays
                WHERE lower(holidayTitle) = lower('$details->title')";
        $queryResult = utility::query($sql);
        if ($queryResult->num_rows != 0) {
            echo "<p>There is already a holiday named $details->title</p>";
            exit(1);
        }

        echo "<p>Adding to database</p>";

        // https://stackoverflow.com/questions/17112852/get-the-new-record-primary-key-id-from-mysql-insert-query
        // https://www.php.net/manual/en/mysqli.insert-id.php
        $sql = "INSERT INTO LCG_holidays (
                    holidayTitle, holidayDescription,
                    holidayDuration, locationID,
                    catID, holidayPrice
                )
                VALUES (
                    '$details->title', '$details->description',
                    '$details->duration', '$details->locID',
                    '$details->catID', '$details->price'
                )";
        utility::query($sql);
        $id = mysqli_insert_id($conn);
        echo "<p>ID: $id</p>";


        // Actually upload the image
        $targetFile = "$root/images/holiday/$id.jpg";
        uploadImage($tmpName, $targetFile);
    }

    function newCategory($desc) {

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