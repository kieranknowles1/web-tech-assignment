<?php
    // https://css-tricks.com/php-include-from-root/
    global $root;
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once "$root/lib/database_conn.php";
    require_once "$root/lib/utility.php";

    $name = utility::tryGet("name", true);
    if (strlen($name) > 40) {
        utility::cleanexit("Max length for name is 40 characters", 400);
    }

    $country = utility::tryGet("country", true);
    if (strlen($country) > 40) {
        utility::cleanexit("Max length for country is 40 characters", 400);
    }

    $locID = $conn->real_escape_string(utility::tryGet("id"));

    if ($locID == null) {
        $title = "New Location";
        require "$root/lib/header.php";

        newLocation($name, $country);
    }
    else {
        // Check that ID is valud
        $sql = "SELECT null
                FROM LCG_location
                WHERE locationID = '$locID'
                LIMIT 1";
        $queryResult = utility::query($sql);
        if ($queryResult->num_rows == 0) {
            utility::cleanExit("Invalid location id $locID", 400);
        }

        // Are we deleting?
        // https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form
        $deleting = utility::tryGet("action", true) == "Delete";

        if ($deleting) {
            $title = "Delete Location";
            require "$root/lib/header.php";
            deleteLocation($locID, $name, $country);
        }
        else {
            $title = "Edit Location";
            require "$root/lib/header.php";
            updateLocation($locID, $name, $country);
        }
    }

    require "$root/lib/footer.php";

    function newLocation($name, $country) {
        global $conn;
        $name_sql = $conn->real_escape_string($name);
        $name_html = htmlspecialchars($name);

        $country_html = htmlspecialchars($country);
        $country_sql = $conn->real_escape_string($country);

        // ID should be an auto increment int
        $locID = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        echo "<p>ID: $locID</p>";

        echo "<p>Name: $name_html</p>";
        echo "<p>Country: $country_html</p>";

        // Check for duplicate
        $dupeSql = "SELECT null
                    FROM LCG_location
                    WHERE lower(locationName) = lower('$name_sql') AND
                          lower(country) = lower('$country_sql');";
        $dupeResult = utility::query($dupeSql);

        if ($dupeResult->num_rows != 0) {
            utility::cleanExit("There is already a location named $name_html, $country_html", 400);
        }

        echo "<p>Adding to database</p>";

        $insertSql = "INSERT INTO LCG_location
                          (locationID, locationName, country)
                      VALUES
                          ('$locID', '$name_sql', '$country_sql');";
        $insertResult = utility::query($insertSql);
    }

    function updateLocation($id, $name, $country) {
        global $conn;
        $name_sql = $conn->real_escape_string($name);
        $name_html = htmlspecialchars($name);

        $country_html = htmlspecialchars($country);
        $country_sql = $conn->real_escape_string($country);

        echo "<p>Updating existing location id=$id</p>";
        
        echo "<p>Name: $name_html</p>";
        echo "<p>Country: $country_html</p>";

        $updateSql = "UPDATE LCG_location
                      SET locationName = '$name_sql',
                          country = '$country_sql'
                      WHERE locationID = '$id'
                      LIMIT 1;";
        $updateResult = utility::query($updateSql);
    }

    function deleteLocation($id, $name, $country) {
        global $conn;
        $name_sql = $conn->real_escape_string($name);
        $name_html = htmlspecialchars($name);

        $country_html = htmlspecialchars($country);
        $country_sql = $conn->real_escape_string($country);

        $unusedSql = "SELECT null FROM LCG_holidays
                      WHERE locationID = '$id';";
        $unusedResult = utility::query($unusedSql);

        if ($unusedResult->num_rows != 0) {
            echo "<p>Could not delete location '$name_html, $country_html' as it is in use by $unusedResult->num_rows holidays</p>";
        }

        echo "<p>Delecting location '$name_html, $country_html'</p>";

        $deleteSql = "DELETE FROM LCG_location
                      WHERE locationID = '$id'
                      LIMIT 1";
        $deleteResult = utility::query($deleteSql);
    }
?>