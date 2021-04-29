<?php
    $root = $_SERVER['DOCUMENT_ROOT'];

    $title = "Admin - Leading Choice Getaways";

    require "$root/lib/header.php";

    require "$root/lib/database_conn.php";
?>
<label>Edit Holiday: 
<select name="editHoliday" onchange="location = this.value;">
    <option selected style="display:none">Select a Holiday</option>
    <?php
        $holidaySql = "SELECT holidayID, holidayTitle
                       FROM LCG_holidays
                       ORDER BY holidayTitle";

        $holidayQuery = utility::query($holidaySql);

        // https://stackoverflow.com/questions/2000656/using-href-links-inside-option-tag
        while ($row = $holidayQuery->fetch_object()) {
            echo "\t<option value='editHoliday.php?id=$row->holidayID'>$row->holidayTitle</option>\n";
        }
    ?>
</select>
</label><br>

<?php

?>

<label>Edit Category: 
<select name="editCategory" onchange="location = this.value;">
    <option selected style="display:none">Select a Category</option>
    <?php
        $categorySql = "SELECT catID, catDesc
                        FROM LCG_category
                        ORDER BY catDesc";

        $categoryQuery = utility::query($categorySql);

        while ($row = $categoryQuery->fetch_object()) {
            echo "\t<option value='editCategory.php?id=$row->catID'>$row->catDesc</option>\n";
        }
    ?>
</select>
</label><br>

<label>Edit Location:
<select name="editLocation" onchange="location = this.value;">
    <option selected style="display:none">Select a Location</option>
    <?php
        $locationSql = "SELECT locationID, locationName, country
                        FROM LCG_location
                        ORDER BY locationName";
        
        $locationQuery = utility::query($locationSql);

        while ($row = $locationQuery->fetch_object()) {
            echo "\t<option value='editLocation.php?id=$row->locationID'>$row->locationName, $row->country</option>\n";
        }
    ?>
</select>
</label><br>

<a href="editHoliday.php">New Holiday</a><br>
<a href="editCategory.php">New Category</a><br>
<a href="editLocation.php">New Location</a>


<?php
    require "$root/lib/footer.php";
?>