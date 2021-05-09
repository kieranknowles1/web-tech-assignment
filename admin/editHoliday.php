<?php
    // Same page is used for new and edit

    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once "$root/lib/utility.php";
    require_once "$root/lib/database_conn.php";

    $extraScripts = ["/admin/scripts.js"];
    
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
        $description = "";
        $duration = "???";
        $price = "???";
        $altText = "";

        $holidayLocation = "Location";
        $holidayCountry = "Country";
        $catDesc = "A [category] holiday";

        $locID = "-1"; // Why is this a varchar()?

        $catID = "-1"; // Same here

        $image = "http://placehold.it/500x250";
        $preview = "url(\"$image\")";
    }
    else {
        // Fill preview
        $isNew = false;
        $title = "Edit holiday";

        $idSanatize = $conn->real_escape_string($holID);

        $sql = "SELECT LCG_holidays.holidayTitle, LCG_holidays.holidayDescription,
                       LCG_holidays.holidayDuration, LCG_holidays.locationID,
                       LCG_holidays.holidayPrice, LCG_holidays.altText,
                       LCG_location.country, LCG_location.locationName,
                       LCG_holidays.catID, LCG_category.catDesc
                FROM LCG_holidays
                INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
                INNER JOIN LCG_category ON LCG_holidays.catID=LCG_category.catID
                WHERE holidayID = '$idSanatize'
                LIMIT 1";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows == 0) {
            utility::cleanExit("Invalid holiday $holID", 400);
        }

        $row = $queryResult->fetch_object();
        $holTitle = htmlspecialchars($row->holidayTitle);
        $description = htmlspecialchars($row->holidayDescription);
        $duration = htmlspecialchars($row->holidayDuration);
        $price = (int)htmlspecialchars($row->holidayPrice);
        $altText = htmlspecialchars($row->altText, $flags = ENT_QUOTES);

        $location = $row->locationName;

        $category = htmlspecialchars(strtolower($row->catDesc));
        $aOrAn = strstr("aeiou", $category[0]) ? 'An' : 'A';
        $catDesc = "$aOrAn $category holiday";

        $country = $row->country;
        $locID = $row->locationID;

        $catID = $row->catID;

        $image = "/images/holiday/$holID.jpg";
        $preview = "url(\"$image\")";
    }

    // Locations
    $sql = "SELECT locationID, country,
                   locationName
            FROM LCG_location
            ORDER BY locationName, country";
    $locQuery = utility::query($sql);

    // Categories
    $sql = "SELECT catID, catDesc
            FROM LCG_category
            ORDER BY catDesc";
    $catQuery = utility::query($sql);

    require "$root/lib/header.php";
?>
<form method="post" action="editHolidayProcess.php" enctype="multipart/form-data">
    <label>Title: 
        <input type="text" name="title" value="<?php echo $holTitle?>" onchange="setText(['#preview-title', '#full-title'], this.value)" maxlength="256" required>
    </label><br>

    <label>Location:
        <select name="locID" onchange="updateLocation(this)" required>
            <option <?php if ($locID=="-1") echo "selected"?> value="" disabled>Select a Location</option>
            <?php
                while ($row = $locQuery->fetch_object()) {
                    $curLocID = $row->locationID;

                    $autoSelect = "";
                    if ($locID == $curLocID) $autoSelect = "selected";

                    $location = htmlspecialchars($row->locationName);
                    $country = htmlspecialchars($row->country);

                    echo "\t<option $autoSelect value='$curLocID' id='loc$curLocID'>$location, $country</option>\n";
                }
            ?>
        </select>
    </label><br>

    <label>Category: 
        <select name="catID" onchange="updateCategory(this)" required>
            <option <?php if ($catID=="-1") echo "selected"?> value="" disabled>Select a Category</option>
            <?php
                while ($row = $catQuery->fetch_object()) {
                    $curCatID = $row->catID;

                    $autoSelect = "";
                    if ($catID == $curCatID) $autoSelect = "selected";

                    $id = htmlspecialchars($row->catID);
                    $desc = htmlspecialchars($row->catDesc);

                    echo "\t<option $autoSelect value='$curCatID' id='cat$id'>$desc</option>\n";
                }
            ?>
        </select>
    </label><br>

    <label>Duration: 
        <input type="number" min="1" max="99" name="duration" value="<?php if(!$isNew) echo $duration?>" onchange="setText(['#preview-duration', '#full-duration'], this.value + ' nights') //$('#preview-duration').text(this.value + ' nights')" required>
    </label><br>

    <label>Description: 
        <textarea name="description" placeholder="Description" onchange="setText(['#full-desc'], this.value)" maxlength="256" required><?php echo $description?></textarea>
    </label><br>

    <label>Price: 
        <input type="number" min="1" max="9999.99" name="price" value="<?php if(!$isNew) echo $price?>" onchange="setText(['#preview-price', '#full-price'], this.value)// $('#preview-price').text('£' + this.value)" required>
    </label><br>

    <label>Image (max 500kb): 
        <input type="file" name="image" accept="image/jpeg" onchange="previewBackground(this, '#preview-img', '#full-img')" <?php if($isNew) echo "required";?>><!-- See scripts.js -->
    </label><br>

    <label>Alt text:
        <textarea name="altText" placeholder="Alt text" onchange="$('#full-img').attr('alt', this.value)" maxlength="256" required><?php echo $altText?></textarea>
    </label><br>

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

    <!-- Preview -->
    <?php
        if ($holTitle == "") $holTitle = "Title";
    ?>
    
    <div class="holidayBox" id="preview-img" style='background-image: <?php echo $preview; ?>'>

    <!-- 
        echo "<p class='priceDuration'>
                $duration nights<br>
                £$price
              </p>";
        
        echo "<p class='country'>$location, $country</p>";
    -->
        <p class="title">
            <span id="preview-title"><?php echo $holTitle; ?></span>
        </p>
        <p class="priceDuration">
            <span id="preview-duration"><?php echo $duration?> nights</span><br>
            <span id="preview-price">£<?php echo $price?></span>
        </p>
    </div>

    <section class="holidayDetails">
        <div class='header'><h2 class='header' id="full-title"><?php echo $holTitle?></h2></div>
        <div class='type' id='full-category'><?php echo $catDesc?></div>
        <div class='description' id="full-desc"><?php echo $description?></div>
        <div class='location' id="full-location"><?php echo $holidayLocation?></div>
        <div class='country' id="full-country"><?php echo $holidayCountry?></div>
        <div class='images'><img src='<?php echo $image?>' id="full-img" alt='<?php echo $altText?>'></div>
        <div class='duration' id='full-duration'><?php echo $duration?> nights</div>
        <div class='price' id='full-price'>£<?php echo $price?></div>
        <a class='book' href=''><p>Book now!</p></a>
    </section>
    <!-- End preview -->
<?php
    require "$root/lib/footer.php";
?>