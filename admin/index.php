<?php
    $root = $_SERVER['DOCUMENT_ROOT'];

    $title = "Admin - Leading Choice Getaways";

    require "$root/lib/header.php";

    require "$root/lib/database_conn.php";
    
    $sql = "SELECT holidayID, holidayTitle
                FROM LCG_holidays
                ORDER BY holidayTitle";
    
    $queryResult = utility::query($sql);
?>
<label>Edit Holiday: 
<select name="editHoliday" onchange="location = this.value;">
    <option selected style="display:none">Select a Holiday</option>
    <?php
        // https://stackoverflow.com/questions/2000656/using-href-links-inside-option-tag
        while ($row = $queryResult->fetch_object()) {
            echo "\t<option value='editHoliday.php?id=$row->holidayID'>$row->holidayTitle</option>\n";
        }
    ?>
</select>
</label><br>

<?php
    $sql = "SELECT catID, catDesc
                FROM LCG_category
                ORDER BY catDesc";
    
    $queryResult = utility::query($sql);
?>

<label>Edit Category: 
<select name="editCategory" onchange="location = this.value;">
    <option selected style="display:none">Select a Category</option>
    <?php
        while ($row = $queryResult->fetch_object()) {
            echo "\t<option value='editCategory.php?id=$row->catID'>$row->catDesc</option>\n";
        }
    ?>
</select>
</label><br>

<a href="editHoliday.php">New Holiday</a><br>
<a href="editCategory.php">New Category</a>


<?php
    require "$root/lib/footer.php";
?>