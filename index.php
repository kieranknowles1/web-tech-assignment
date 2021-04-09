<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Leading Choice Getaways</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<?php
    require "header.php";
    require "database_conn.php";

    // Shared by holidays and categories
    function boxBegin($id, $type ,$title) {

        // Use a default background image
        $background = "images/$type/$id.jpg";
        if (!file_exists($background)) {
            $background = "images/default_background.jpg";
        }

        echo "<a href='category.php?id={$id}'><div class='holidayBox' style='background-image: url(\"$background\")'>\n";

        echo "\t<p class='title'><span>{$title}</span>";
    }

    function boxEnd() {
        echo "</div></a>\n";
    }
?>

<section class="banner">
    <h2>Luxury and specialist holidays in a couple of clicks</h2>
</section>

<section class="categories">
    <h2>Categories</h2>
    <div class="break"></div>
<?php
    // Categories
    $sql = "SELECT catID, catDesc
            FROM LCG_category";
    
    $queryResult = $conn->query($sql);

    if ($queryResult === false) {
        $error = $conn->error;
        echo "<p>Category query failed: $error.</p>";
        exit;
    }

    while ($row = $queryResult->fetch_object()) {
        // Use inline css for backgroud
        boxBegin($row->catID, "category", $row->catDesc);
        echo "</p>\n";

        boxEnd();
    }
?>
</section>

<section class="holidays">
    <h2>Holdiays</h2>
    <div class="break"></div>
<?php
    // Holidays
    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.country
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID";

    $queryResult = $conn->query($sql);

    
    if ($queryResult === false) {
        $error = $conn->error;
        echo "<p>Holiday query failed: $error.</p>";
        exit;
    }

    while ($row = $queryResult->fetch_object()) {
        boxBegin($row->holidayID, "holiday", $row->holidayTitle);

        echo "<span class='country'>$row->country</span></p>\n";
        
        echo "<p>$row->holidayDuration nights</p>";

        // holidayPrice uses decimal so query returns a string
        // Casting to int removes the trailing '.00'
        $price = (int)$row->holidayPrice;
        echo "<p>£$price</p>";

        boxEnd();
    }
?>
</section>

<?php
    require "footer.php";
?>