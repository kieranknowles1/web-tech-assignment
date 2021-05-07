<?php
    // Same page is used for new and edit

    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once "$root/lib/utility.php";
    require_once "$root/lib/database_conn.php";

    $extraScripts = ["/admin/scripts.js"];
    
    // Cant use boxbegin() and boxend() as they don't allow setting ids
    // require_once "$root/lib/shared.php";

    $locID =  utility::tryGet("id");
    if ($locID == null) {
        // New category
        $isNew = true;
        $title = "New location";

        $locationName = "";
        $country = "";
    }
    else {
        // Fill preview
        $isNew = false;
        $title = "Edit location";

        $idSanatize = $conn->real_escape_string($locID);

        $sql = "SELECT locationName, country
                FROM LCG_location
                WHERE locationID = '$idSanatize'
                LIMIT 1";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows == 0) {
            utility::cleanExit("Invalid location $locID", 400);
        }

        $row = $queryResult->fetch_object();
        
        $locationName = htmlspecialchars($row->locationName);
        $country = htmlspecialchars($row->country);

        // Check for usage
        $sql = "SELECT null
                FROM LCG_holidays
                WHERE locationID = '$idSanatize'";
        $queryResult = utility::query($sql);
        $numUses = $queryResult->num_rows;
    }

    require "$root/lib/header.php";
?>
<form method="post" action="editLocationProcess.php">
    <label>Name:
        <input type="text" name="name" value="<?php echo $locationName?>" required>
    </label><br>

    <label>Country:
        <input type="text" name="country" value="<?php echo $country?>" required>
    </label><br>

    <?php if (!$isNew) echo "<input type='hidden' name='id' value='$locID'>"?>

    <input type="submit" name="action" value="Submit">
    <?php
        if (!$isNew) {
            echo "<input type='submit' name='action' value='Delete' formnovalidate onclick='confirmDelete(event, \"location\");' ";
            if ($numUses != 0) {
                echo "disabled title='Cannot delete, location is in use by $numUses holidays'";
            }
            echo ">";
        }
    ?>
</form>
<?php
    require "$root/lib/footer.php";
?>