<?php
    // https://css-tricks.com/php-include-from-root/
    $root = $_SERVER['DOCUMENT_ROOT'];
    $title = "New Category";

    require "$root/lib/header.php";
    
    // Cant use boxbegin() and boxend() as they don't allow setting ids
    // require_once "$root/lib/shared.php";

    // https://www.w3schools.com/php/php_file_upload.asp
?>
<form method="post" action="newCategoryProcess.php" enctype="multipart/form-data">
    <label>Description: 
        <input type="text" name="desc" onchange="$('#preview-title').text(this.value)" required></input>
    </label><br>

    <label>Image (max 500kb): 
        <input type="file" name="image" accept="image/jpeg" onchange="previewBackground(this, '#preview-img')" required><!-- See scripts.js -->
    </label><br>
    <!--<img id="preview" class="preview" src="http://placehold.it/500x300" alt="your image" /><br>-->


    <div class='holidayBox' id="preview-img" style='background-image: url("http://placehold.it/500x250")'>
        <p class='title' id="preview-title"><span>Title</span>

    </div>


    <input type="submit" value="Submit">
</form>
<?php
    require "$root/lib/footer.php";
?>