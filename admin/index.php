<?php
    $root = $_SERVER['DOCUMENT_ROOT'];

    $title = "Admin - Leading Choice Getaways";

    require "$root/lib/header.php";

    require "$root/lib/database_conn.php";
?>
<table class="adminTable">
    <tr>
        <td><label for="editHoliday">Holiday: </label></td>
        <td><select name="editHoliday" id="editHoliday" class="adminSelect" onchange="location = this.value;">
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
        </select></td>
        <td><a href="editHoliday.php">New</a></td>
    </tr>

    <tr>
        <td><label for="editCategory">Category: </label></td>
        <td><select name="editCategory" id="editCategory" class="adminSelect" onchange="location = this.value;">
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
        </select></td>
        <td><a href="editCategory.php">New</a></td>
    </tr>

    <tr>
        <td><label for="editLocation">Location: </label></td>
        <td><select name="editLocation" id="editLocation" class="adminSelect" onchange="location = this.value;">
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
        </select></td>
        <td><a href="editLocation.php">New</a></td>
    </tr>
</table>

<?php
    require "$root/lib/footer.php";
?>