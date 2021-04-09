<?php
// Use the same functions for holidays and categories

// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    http_response_code(403);
    exit("Direct access not permitted.");
}

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

function makeHoliday($row) {
    boxBegin($row->holidayID, "holiday", $row->holidayTitle);

    echo "<span class='country'>$row->country</span></p>\n";
    
    echo "<p>$row->holidayDuration nights</p>";

    // holidayPrice uses decimal so query returns a string
    // Casting to int removes the trailing '.00'
    $price = (int)$row->holidayPrice;
    echo "<p>£$price</p>";

    boxEnd();
}

?>