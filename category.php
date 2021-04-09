<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    const ERROR_MESSAGE = "<p>Invalid category</p>";

    require "utility.php";
    require "database_conn.php";
    require "shared.php";

    $catID = utility::tryGet("id");
    if ($catID == null) {
        http_response_code(400);
        echo ERROR_MESSAGE;
        exit;
    }
    $catID = $conn->real_escape_string($catID);


    $sql = "SELECT catDesc
            FROM LCG_category
            WHERE catID = '$catID'
            LIMIT 1";
    
    $queryResult = $conn->query($sql);

    utility::checkQuery($conn, $queryResult);

    if($queryResult->num_rows == 0) {
        http_response_code(400);
        echo ERROR_MESSAGE;
        exit;
    }

    $row = $queryResult->fetch_object();
    $catDesc = $row->catDesc;
    echo "<title>$catDesc - Leading Choice Getaways</title>\n";
    ?>
    

    <meta charset="utf-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<?php
    require "header.php";

    echo "  <section class='holidays'>
                <h2>$catDesc holidays</h2>
                <div class='break'></div>";

    $sql = "SELECT LCG_holidays.holidayID, LCG_holidays.holidayTitle, LCG_holidays.holidayDuration, LCG_holidays.holidayPrice,
                   LCG_location.country
            FROM LCG_holidays
            INNER JOIN LCG_location ON LCG_holidays.locationID=LCG_location.locationID
            WHERE LCG_holidays.catID='$catID'";

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