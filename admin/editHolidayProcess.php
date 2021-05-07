<?php

    class HolidayDetails {
        /*public $title;
        public $description;
        public $duration;
        public $locID;
        public $catID;
        public $price;
        public $altText;*/
        public $title_sql;
        public $description_sql;
        public $duration_sql;
        public $locID_sql;
        public $catID_sql;
        public $price_sql;
        public $altText_sql;
        public $title_html;
        public $description_html;
        public $duration_html;
        public $locID_html;
        public $catID_html;
        public $price_html;
        public $altText_html;
    }

    function printDetails($details) {
        echo "<p>Title: $details->title_html</p>\n";
        echo "<p>Description: $details->description_html</p>\n";
        echo "<p>Duration: $details->duration_html</p>\n";
        echo "<p>locID: $details->locID_html</p>\n";
        echo "<p>catID: $details->catID_html</p>\n";
        echo "<p>Price: $details->price_html</p>\n";
        echo "<p>Alt text: $details->altText_html</p>\n";
    }

    // https://css-tricks.com/php-include-from-root/
    global $root;
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once "$root/lib/database_conn.php";
    require_once "$root/lib/utility.php";

    require_once "imageLib.php";

    //print_r($_REQUEST);
    
    $details = new HolidayDetails;

    $title = utility::tryGet("title", true);
    if (strlen($title) > 256) {
        utility::cleanExit("Max length for title is 256 characters", 400);
    }

    $description = utility::tryGet("description", true);
    if (strlen($description) > 256) {
        utility::cleanExit("Max length for description is 256 characters", 400);
    }

    $duration = utility::tryGet("duration", true);
    if ($duration < 1 || $duration > 99) {
        utility::cleanExit("Duration must be between 1 and 99 days", 400);
    }

    $locID = utility::tryGet("locID", true);
    $catID = utility::tryGet("catID", true);
    $price = utility::tryGet("price", true);
    if ($price < 1 || $price > 9999.99) {
        utility::cleanExit("Price must be between £1 and £9999.99", 400);
    }

    $altText = utility::tryGet("altText", true);
    if (strlen($altText) > 256) {
        utility::cleanExit("Max length for alt text is 256 characters", 400);
    }

    $details->title_sql = $conn->real_escape_string($title);
    $details->description_sql = $conn->real_escape_string($description);
    $details->duration_sql = $conn->real_escape_string($duration);
    $details->locID_sql = $conn->real_escape_string($locID);
    $details->catID_sql = $conn->real_escape_string($catID);
    $details->price_sql = $conn->real_escape_string($price);
    $details->altText_sql = $conn->real_escape_string($altText);

    $details->title_html = htmlspecialchars($title);
    $details->description_html = htmlspecialchars($description);
    $details->duration_html = htmlspecialchars($duration);
    $details->locID_html = htmlspecialchars($locID);
    $details->catID_html = htmlspecialchars($catID);
    $details->price_html = htmlspecialchars($price);
    $details->altText_html = htmlspecialchars($altText);

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
            utility::cleanExit("Invalid holiday id $holID", 400);
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
            updateHoliday($holID, $details);
        }
    }

    require "$root/lib/footer.php";

    function newHoliday($details) {
        global $root, $conn;

        // Not necessary as LCG_holidays uses the correct type for the ID
        // https://stackoverflow.com/questions/6296822/how-to-format-numbers-with-00-prefixes-in-php
        //$catID = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        //echo "<p>ID: $catID</p>";

        $tmpName = checkForImage("image", true);
        /*
        public $title;
        public $description;
        public $duration;
        public $locID;
        public $catID;
        public $price;
        */
        printDetails($details);

        // Check for duplicate
        $sql = "SELECT NULL
                FROM LCG_holidays
                WHERE lower(holidayTitle) = lower('$details->title_sql')";
        $queryResult = utility::query($sql);
        if ($queryResult->num_rows != 0) {
            utility::cleanExit("There is already a holiday named $details->title_html", 400);
        }

        // Validate category and location
        $categorySql = "SELECT null
                        FROM LCG_category
                        WHERE catID = '$details->catID_sql'";
        $categoryQuery = utility::query($categorySql);
        if ($categoryQuery->num_rows == 0) {
            utility::cleanExit("Invalid category ID '$details->catID_html'", 400);
        }

        $locationSql = "SELECT null
                        FROM LCG_location
                        WHERE locationID = '$details->locID_sql'";
        $locationQuery = utility::query($locationSql);
        if ($locationQuery->num_rows == 0) {
            utility::cleanExit("Invalid location ID '$details->locID_html'", 400);
        }

        echo "<p>Adding to database</p>";

        // https://stackoverflow.com/questions/17112852/get-the-new-record-primary-key-id-from-mysql-insert-query
        // https://www.php.net/manual/en/mysqli.insert-id.php
        $sql = "INSERT INTO LCG_holidays (
                    holidayTitle, holidayDescription,
                    holidayDuration, locationID,
                    catID, holidayPrice,
                    altText
                )
                VALUES (
                    '$details->title_sql', '$details->description_sql',
                    '$details->duration_sql', '$details->locID_sql',
                    '$details->catID_sql', '$details->price_sql',
                    '$details->altText_sql'
                )";
        utility::query($sql);
        $id = mysqli_insert_id($conn);
        echo "<p>ID: $id</p>";


        // Actually upload the image
        $targetFile = "$root/images/holiday/$id.jpg";
        uploadImage($tmpName, $targetFile);
    }

    function updateHoliday($id, $details) {
        global $root;

        printDetails($details);

        echo "<p>Updating existing holiday $id</p>";

        $sql = "UPDATE LCG_holidays
                SET holidayTitle = '$details->title_sql',
                    holidayDescription = '$details->description_sql',
                    holidayDuration = '$details->duration_sql',
                    locationID = '$details->locID_sql',
                    catID = '$details->catID_sql',
                    holidayPrice = '$details->price_sql',
                    altText = '$details->altText_sql'
                WHERE holidayID = $id
                LIMIT 1"; // Safety
        $queryResult = utility::query($sql);

        // Upload image if provided
        $tmpName = checkForImage("image", false);
        if ($tmpName != null) {
            $imgFile = "$root/images/holiday/$id.jpg";
            uploadImage($tmpName, $imgFile);
        }
    }

    function deleteHoliday($id) {
        global $root;

        echo "<p>Deleting holiday $id</p>";

        $sql = "DELETE FROM LCG_holidays
                WHERE holidayID = '$id'
                LIMIT 1"; // Safety
        $queryResult = utility::query($sql);

        $imgFile = "$root/images/holiday/$id.jpg";
        echo "<p>Image: $imgFile</p>";
        unlink($imgFile);
    }
?>