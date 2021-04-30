<?php
    const ERROR_MESSAGE = "<p>Invalid holiday</p>";

    require_once "lib/utility.php";
    require_once "lib/database_conn.php";
    require_once "lib/shared.php";

    $id = utility::tryGet("id");
    if ($id == null) {
        utility::cleanExit(ERROR_MESSAGE);
    }
    $id = $conn->real_escape_string($id);


    $sql = "SELECT LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_holidays.holidayDescription, LCG_holidays.altText,
                   LCG_location.locationName, LCG_location.country,
                   LCG_category.catDesc
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            INNER JOIN LCG_category ON LCG_holidays.catID=LCG_category.catID
            WHERE LCG_holidays.holidayID='$id'
            LIMIT 1";
    
    $queryResult = utility::query($sql);

    if($queryResult->num_rows == 0) {
        utility::cleanExit(ERROR_MESSAGE);
    }

    $row = $queryResult->fetch_object();
    $holidayTitle = $row->holidayTitle;
    $title = "$holidayTitle - Leading Choice Getaways";

    require_once "lib/header.php";
?>

<section class="holidayDetails">
<?php
    $category = strtolower($row->catDesc);
    // Automatically use 'an' when necessary
    $aOrAn = strstr("aeiou", $category[0]) ? 'An' : 'A';

    $image = "images/holiday/$id";
    if (!file_exists("$image.jpg")) {
        $image = "images/default_background";
    }

    echo "\t<div class='header'><h2 class='header'>$row->holidayTitle</h2></div>\n";
    echo "\t<div class='type'>$aOrAn $category holiday</div>\n";
    echo "\t<div class='description'>$row->holidayDescription</div>\n";
    echo "\t<div class='location'>$row->locationName</div>\n";
    echo "\t<div class='country'>$row->country</div>\n";
    echo "\t<div class='images'><img src='$image.jpg' alt='$row->altText'></div>\n";
    echo "\t<div class='duration'>$row->holidayDuration nights</div>\n";
    echo "\t<div class='price'>£$row->holidayPrice</div>\n";
    echo "\t<a class='book' href=''><p>Book now!</p></a>\n"
?>
</section>

<?php
    require "lib/footer.php";
?>