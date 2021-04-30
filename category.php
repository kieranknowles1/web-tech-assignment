<?php
    const ERROR_MESSAGE = "Invalid category";

    require_once "lib/utility.php";
    require_once "lib/database_conn.php";
    require_once "lib/shared.php";

    $catID = utility::tryGet("id");
    if ($catID == null) {
        utility::cleanExit(ERROR_MESSAGE, 400);
    }
    $catID = $conn->real_escape_string($catID);


    $sql = "SELECT catDesc
            FROM LCG_category
            WHERE catID = '$catID'
            LIMIT 1";

    $queryResult = utility::query($sql);

    if($queryResult->num_rows == 0) {
        utility::cleanExit(ERROR_MESSAGE, 400);
    }

    $row = $queryResult->fetch_object();
    $catDesc = $row->catDesc;
    $title = "$catDesc holidays - Leading Choice Getaways";

    require_once "lib/header.php";

    echo "  <section class='holidays'>
                <h2>$catDesc holidays</h2>
                <div class='break'></div>";

    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.country, LCG_location.locationName
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            WHERE LCG_holidays.catID='$catID'";

    $queryResult = utility::query($sql);

    holidayList($queryResult);
    ?>

    </section>

<?php
    require "lib/footer.php";
?>