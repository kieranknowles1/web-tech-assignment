<?php
    $title = "Leading Choice Getaways";
    require "lib/header.php";
    
    require_once "lib/database_conn.php";
    require_once "lib/utility.php";
    require_once "lib/shared.php";
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
            FROM LCG_category
            ORDER BY catDesc";
    
    $queryResult = utility::query($sql);

    while ($row = $queryResult->fetch_object()) {
        $id = htmlspecialchars($row->catID);
        $description = htmlspecialchars($row->catDesc);

        // Use inline css for backgroud
        boxBegin($id, "category", $description);
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
                   LCG_location.country, LCG_location.locationName, LCG_holidays.holidayDescription
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            ORDER BY LCG_holidays.holidayTitle";

    $queryResult = utility::query($sql);

    holidayList($queryResult);
?>
</section>

<?php
    require "lib/footer.php";
?>