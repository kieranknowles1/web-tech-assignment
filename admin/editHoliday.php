<?php
    // Same page is used for new and edit

    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once "$root/lib/utility.php";
    require_once "$root/lib/database_conn.php";
    
    // Cant use boxbegin() and boxend() as they don't allow setting ids
    // require_once "$root/lib/shared.php";

    // https://www.w3schools.com/php/php_file_upload.asp

    // Check if editing existing
    $holID =  utility::tryGet("id");
    if ($holID == null) {
        // New
        $isNew = true; // Allow keeping existing image
        $title = "New holiday";

        $holTitle = "";
        $duration = "???";
        $price = "???";

        $country = "Country";
        $locID = "-1"; // Why is this a varchar()?

        $catID = "-1"; // Same here

        $preview = "url(\"http://placehold.it/500x250\")";
    }
    else {
        // Fill preview
        $isNew = false;
        $title = "Edit holiday";

        $idSanatize = $conn->real_escape_string($holID);

        $sql = "SELECT LCG_holidays.holidayTitle, LCG_holidays.holidayDescription,
                       LCG_holidays.holidayDuration, LCG_holidays.locationID,
                       LCG_holidays.holidayPrice,
                       LCG_location.country,
                       LCG_holidays.catID
                FROM LCG_holidays
                INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
                WHERE holidayID = '$idSanatize'
                LIMIT 1";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows == 0) {
            echo "<p>Invalid category $holID</p>";
            exit(1);
        }

        $row = $queryResult->fetch_object();
        $holTitle = $row->holidayTitle;
        $duration = $row->holidayDuration;
        $price = (int)$row->holidayPrice;

        $country = $row->country;
        $locID = $row->locationID;

        $catID = $row->catID;

        $preview = "url(\"/images/holiday/$holID.jpg\")";
    }

    // Locations
    $sql = "SELECT locationID, country,
                   locationName
            FROM LCG_location";
    $locQuery = utility::query($sql);

    // Categories
    $sql = "SELECT catID, catDesc
            FROM LCG_category";
    $catQuery = utility::query($sql);

    require "$root/lib/header.php";
?>
<form method="post" action="editHolidayProcess.php" enctype="multipart/form-data">
    <label>Title: 
        <input type="text" name="title" value="<?php echo $holTitle?>" onchange="$('#preview-title').text(this.value)" required>
    </label><br>

    <label>Location:
        <select name="locID" onchange="updateLocation(this)" required>
            <option <?php if ($locID=="-1") echo "selected"?> value="" disabled>Select a Location</option>
            <?php
                while ($row = $locQuery->fetch_object()) {
                    $curLocID = $row->locationID;
                    $locName = $row->locationName;
                    $locCountry = $row->country;

                    echo "\t<option value='$curLocID' id='loc$curLocID'>$locName, $locCountry</option>\n";
                }
            ?>
        </select>
    </label><br>

    <label>Category: 
        <select name="catID">
            <option <?php if ($catID=="-1") echo "selected"?> value="" disabled>Select a Category</option>
            <?php
                while ($row = $catQuery->fetch_object()) {
                    $curCatID = $row->catID;
                    $curCatName = $row->catDesc;

                    echo "\t<option value='$curCatID'>$curCatName</option>\n";
                }
            ?>
        </select>
    </label><br>

    <label>Duration: 
        <input type="number" min="1" name="duration" value="<?php echo $duration?>" onchange="$('#preview-duration').text(this.value + ' nights')" required>
    </label><br>

    <label>Description: 
        <textarea name="description" placeholder="Description" required></textarea>
    </label><br>

    <label>Price: 
        <input type="number" min="1" name="price" value="<?php echo $price?>" onchange="$('#preview-price').text('£' + this.value)" required>
    </label><br>

    <label>Image (max 500kb): 
        <input type="file" name="image" accept="image/jpeg" onchange="previewBackground(this, '#preview-img')" <?php if($isNew) echo "required";?>><!-- See scripts.js -->
    </label><br>

    <!-- Preview -->
    
    <div class="holidayBox" id="preview-img" style='background-image: <?php echo $preview; ?>'>
        <p class="title">
            <span id="preview-title"><?php echo $holTitle; ?></span>
            <span class="country" id="preview-location"><?php echo $country?></span>
        </p>
        <p id="preview-duration"><?php echo $duration?> nights</p>
        <p id="preview-price">£<?php echo $price?></p>
    </div>
    <!-- End preview -->

    <?php if (!$isNew) echo "<input type='hidden' name='id' value='$holID'>" /* Send ID of existing holiday */?>

    <!-- https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form -->
    <!-- https://stackoverflow.com/questions/18725078/bypass-html-required-attribute-when-submitting -->
    <input type="submit" name="action" value="Submit">
    <?php
        if (!$isNew) {
            echo "<input type='submit' name='action' value='Delete' formnovalidate onclick='confirmDelete(event, \"holiday\");'>";
        }
    ?>
</form>
<?php
    require "$root/lib/footer.php";
?>