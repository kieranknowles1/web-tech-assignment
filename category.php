<?php
    const ERROR_MESSAGE = "<p>Invalid category</p>";

    // echo "util";
    require_once "utility.php";
    // echo "conn";
    require_once "database_conn.php";
    require_once "shared.php";

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
    $title = "$catDesc holidays - Leading Choice Getaways";

    require_once "header.php";

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

    utiliy::holidayList($queryResult);
    ?>

    </section>

<?php
    require "footer.php";
?>