<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/utility.php";
utility::noDirectAccess();

// Use the same functions for holidays and categories

function boxBegin($id, $type ,$title) {

    // Use a default background image
    $background = "images/$type/$id.jpg";
    if (!file_exists($background)) {
        $background = "images/default_background.jpg";
    }

    echo "<a href='$type.php?id={$id}'><div class='holidayBox' style='background-image: url(\"$background\")'>\n";

    echo "\t<p class='title'><span>{$title}</span>";
}

function boxEnd() {
    echo "</div></a>\n";
}

// Now using holidayList
/*
function makeHoliday($row) {
    boxBegin($row->holidayID, "holiday", $row->holidayTitle);

    echo "<span class='country'>$row->country</span></p>\n";
    
    echo "<p>$row->holidayDuration nights</p>";

    // holidayPrice uses decimal so query returns a string
    // Casting to int removes the trailing '.00'
    $price = (int)$row->holidayPrice;
    echo "<p>£$price</p>";

    boxEnd();
}*/

function holidayList($queryResult) {
    while ($row = $queryResult->fetch_object()) {
        // TODO: Improve formatting when number of results is not a multiple of 3

        boxBegin($row->holidayID, "holiday", $row->holidayTitle);

        echo "<span class='country'>$row->locationName, $row->country</span></p>\n";
        
        echo "<p>$row->holidayDuration nights</p>";
    
        // holidayPrice uses decimal so query returns a string
        // Casting to int removes the trailing '.00'
        $price = (int)$row->holidayPrice;
        echo "<p>£$price</p>";
    
        boxEnd();
    }
}

?>