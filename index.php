<?php
    $title = "Leading Choice Getaways";
    require "header.php";
    
    require_once "database_conn.php";
    require_once "utility.php";
    require_once "shared.php";
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

    utility::checkQuery($conn, $queryResult);

    while ($row = $queryResult->fetch_object()) {
        // Use inline css for backgroud
        boxBegin($row->catID, "category", $row->catDesc);
        echo "</p>\n";

        boxEnd();
    }
?>
</section>

<section class="holidays">
    <h2>Holidays</h2>
    <div class="break"></div>
<?php
    // Holidays
    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.country
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID";

    $queryResult = $conn->query($sql);

    
    utility::checkQuery($conn, $queryResult);

    while ($row = $queryResult->fetch_object()) {
        makeHoliday($row);
    }
?>
</section>

<?php
    require "footer.php";
?>