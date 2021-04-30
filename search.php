<?php
    const ERROR_MESSAGE = "<p>No search query</p>";

    require "lib/utility.php";
    require "lib/database_conn.php";
    require "lib/shared.php";

    $searchQuery = utility::tryGet("q");
    if ($searchQuery == null) {
        utility::cleanExit(ERROR_MESSAGE, 400);
    }
    $searchQuery = $conn->real_escape_string($searchQuery);

    $title = "$searchQuery - Leading Choice Getaways";

    require "lib/header.php";
    

    echo "  <section class='holidays'>
                <h2>Search results</h2>
                <div class='break'></div>";

    // https://www.w3schools.com/sql/sql_like.asp
    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.locationName, LCG_location.country, LCG_holidays.holidayDescription
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            WHERE LCG_holidays.holidayTitle LIKE '%$searchQuery%'
            OR    LCG_location.country LIKE '%$searchQuery%'
            OR    LCG_location.locationName LIKE '%$searchQuery%'";

    $queryResult = utility::query($sql);
    if ($queryResult->num_rows != 0) {
        holidayList($queryResult);
    }
    else {
        echo "<p>No results</p>";
    }
    ?>

    </section>

<?php
    require "lib/footer.php";
?>