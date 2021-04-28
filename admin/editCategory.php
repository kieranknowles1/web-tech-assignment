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

    // Check if editing an existing category
    $catID =  utility::tryGet("id");
    if ($catID == null) {
        // New category
        $isNew = true; // Allow keeping existing image
        $title = "New category";

        $catDesc = "";
        $preview = "url(\"http://placehold.it/500x250\")";
    }
    else {
        // Fill preview
        $isNew = false;
        $title = "Edit category";

        $idSanatize = $conn->real_escape_string($catID);

        $sql = "SELECT catDesc
                FROM LCG_category
                WHERE catID = '$idSanatize'
                LIMIT 1";
        $queryResult = utility::query($sql);

        if ($queryResult->num_rows == 0) {
            echo "<p>Invalid category $catID</p>";
            exit(1);
        }

        $row = $queryResult->fetch_object();
        $catDesc = $row->catDesc;

        $preview = "url(\"/images/category/$catID.jpg\")";

        // Check for usage
        $sql = "SELECT null
                FROM LCG_holidays
                WHERE catID = '$idSanatize'";
        $queryResult = utility::query($sql);
        $numUses = $queryResult->num_rows;
    }

    require "$root/lib/header.php";
?>
<form method="post" action="editCategoryProcess.php" enctype="multipart/form-data">
    <label>Title: 
        <input type="text" name="desc" value="<?php echo $catDesc?>" onchange="$('#preview-title').text(this.value)" required>
    </label><br>

    <label>Image (max 500kb): 
        <input type="file" name="image" accept="image/jpeg" onchange="previewBackground(this, '#preview-img')" <?php if($isNew) echo "required";?>><!-- See scripts.js -->
    </label><br>


    <div class='holidayBox' id="preview-img" style='background-image: <?php echo $preview; ?>'>
        <p class='title' id="preview-title"><span><?php echo $catDesc; ?></span>

    </div>

    <?php if (!$isNew) echo "<input type='hidden' name='id' value='$catID'>" /* Send ID of existing category */?>

    <!-- https://stackoverflow.com/questions/547821/two-submit-buttons-in-one-form -->
    <!-- https://stackoverflow.com/questions/18725078/bypass-html-required-attribute-when-submitting -->
    <input type="submit" name="action" value="Submit">
    <?php
        if (!$isNew) {
            echo "<input type='submit' name='action' value='Delete' formnovalidate onclick='confirmDelete(event, \"category\");' ";
            if ($numUses != 0) {
                echo "disabled title='Cannot delete, category is in use by $numUses holidays'";
            }
            echo ">";
        }
    ?>
</form>
<?php
    require "$root/lib/footer.php";
?>