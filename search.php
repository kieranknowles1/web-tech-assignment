<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    const ERROR_MESSAGE = "<p>No search query</p>";

    require "utility.php";
    require "database_conn.php";
    require "shared.php";

    $searchQuery = utility::tryGet("q");
    if ($searchQuery == null) {
        http_response_code(400);
        echo ERROR_MESSAGE;
        exit;
    }
    $searchQuery = $conn->real_escape_string($searchQuery);

    echo "<title>$searchQuery - Leading Choice Getaways</title>\n";
    ?>
    

    <meta charset="utf-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<?php
    require "header.php";
    

    echo "  <section class='holidays'>
                <h2>Search results</h2>
                <div class='break'></div>";

    // https://www.w3schools.com/sql/sql_like.asp
    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.country
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            WHERE LCG_holidays.holidayTitle LIKE '%$searchQuery%'
            OR    LCG_location.country LIKE '%$searchQuery%'";

    $queryResult = $conn->query($sql);
        if ($queryResult->num_rows != 0) {
        utility::checkQuery($conn, $queryResult);

        while ($row = $queryResult->fetch_object()) {
            makeHoliday($row); // TODO: Improve formatting when number of results is not a multiple of 3
        }
    }
    else {
        echo "<p>No results</p>";
    }
    ?>

    </section>

<?php
    require "footer.php";
?>