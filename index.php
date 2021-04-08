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
        echo "<a href='category.php?id={$id}'><div class='holidayBox' style='background-image: url(\"images/$type/$id.jpg\")'>\n";

        echo "\t<p class='title'>{$title}</p>\n";
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

        boxEnd();
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
        boxBegin($row->holidayID, "holiday", $row->holidayTitle);

        boxEnd();
    }
?>
</section>

<?php
    require "footer.php";
?>