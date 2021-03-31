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
<?php
    // Categories
    $sql = "SELECT catID, catDesc
            FROM LCG_category";
    
    $queryResult = $conn->query($sql);

    if ($queryResult === false) {
        $error = $conn->error;
        echo "<p> Query failed: $error.</p>";
        exit;
    }

    while ($row = $queryResult->fetch_object()) {
        echo "<div class='category'><a href='category.php?category={$row->catID}'>";

        echo "<p>{$row->catDesc}</p>";

        echo "</a></div>";
    }
?>
</section>

<?php
    require "footer.php";
?>