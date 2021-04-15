<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    const ERROR_MESSAGE = "<p>Invalid holiday</p>";

    require_once "utility.php";
    require_once "database_conn.php";
    require_once "shared.php";

    $id = utility::tryGet("id");
    if ($id == null) {
        http_response_code(400);
        echo ERROR_MESSAGE;
        exit;
    }
    $id = $conn->real_escape_string($id);


    $sql = "SELECT LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_holidays.holidayDescription,
                   LCG_location.locationName, LCG_location.country,
                   LCG_category.catDesc
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            INNER JOIN LCG_category ON LCG_holidays.catID=LCG_category.catID
            WHERE LCG_holidays.holidayID='$id'
            LIMIT 1";
    
    $queryResult = $conn->query($sql);

    utility::checkQuery($conn, $queryResult);

    if($queryResult->num_rows == 0) {
        http_response_code(400);
        echo ERROR_MESSAGE;
        exit;
    }

    $row = $queryResult->fetch_object();
    $holidayTitle = $row->holidayTitle;
    echo "<title>$holidayTitle holidays - Leading Choice Getaways</title>\n";
    ?>
    

    <meta charset="utf-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<?php
    require "header.php";
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

    // TODO: Use the database for this
    $alt = file_get_contents($image . "_alt.txt");

    echo "\t<div class='header'><h2 class='header'>$row->holidayTitle</h2></div>\n";
    echo "\t<div class='type'>$aOrAn $category holiday</div>\n";
    echo "\t<div class='description'>$row->holidayDescription</div>\n";
    echo "\t<div class='location'>$row->locationName</div>\n";
    echo "\t<div class='country'>$row->country</div>\n";
    echo "\t<div class='images'><img src='$image.jpg' alt='$alt'></div>\n";
    echo "\t<div class='duration'>$row->holidayDuration nights</div>\n";
    echo "\t<div class='price'>£$row->holidayPrice</div>\n";
    echo "\t<a class='book' href=''><p>Book now!</p></a>\n"
?>
</section>

<?php
    require "footer.php";
?>