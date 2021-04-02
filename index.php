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
        // Use inline css for backgroudn
        echo "<div class='category' style='background-image: url(\"images/categories/$row->catID.jpg\")'><a href='category.php?id={$row->catID}'>\n";

        echo "\t<p>{$row->catDesc}</p>\n";

        echo "</a></div>\n";
    }
?>
</section>

<section class="holidays">
    <h2>Holdiays</h2>
    <div class="break"></div>
<?php
    // Holidays
    $sql = "SELECT holidayID, holidayTitle
            FROM LCG_holidays";

    $queryResult = $conn->query($sql);

    
    if ($queryResult === false) {
        $error = $conn->error;
        echo "<p>Holiday query failed: $error.</p>";
        exit;
    }

    while ($row = $queryResult->fetch_object()) {
        echo "<div class='holiday'><a href='holiday.php?id={$row->holidayID}'>\n";

        echo "\t<p>{$row->holidayTitle}</p>\n";

        echo "</a></div>\n";
    }
?>
</section>

<?php
    require "footer.php";
?>