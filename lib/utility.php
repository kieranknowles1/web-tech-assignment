<?php
//utility::noDirectAccess(); // Can only use this when including the file

// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) == 1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    http_response_code(403);
    exit(utility::DIRECT_ACCESS_ERROR);
}

// Functions shared between multiple pages
class utility {
    // Returns $_REQUEST[$key] if it is set
    // DOES NOT SANTISE FOR SQL
    static function tryGet($key, $required = false) {
        $value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        if ($required && $value == null) {
            utility::cleanExit("$key is required");
        }
        else {
            return $value;
        }
    }

    // Exits while including a footer and a link back
    static function cleanExit($message) {
        require_once "header.php"; // Include the header if it hasn't been included yet
        echo "<p>$message</p>";

        // https://stackoverflow.com/questions/8814472/how-to-make-an-html-back-link
        echo "<a href='#' onclick='history.go(-1)'>Back</a>"; // This method keeps form contents
        
        require "footer.php";
        exit(1);
    }

    // Runs then validates a query, returns the result
    static function query($sql) {
        global $conn;
        $result = $conn->query($sql);

        if ($result === false) {
            $error = $conn->error;
            utility::cleanExit("Query failed: $error.");
        }
        else {
            return $result;
        }
    }

    // https://www.php.net/manual/en/language.types.string.php
    const DIRECT_ACCESS_ERROR = <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Error - Leading Choice Getaways</title>
            <link href="style.css" rel="stylesheet" type="text/css">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
            <p>Direct access is not permitted</p>
            <a href="/">Return to home page</a>
        </body>
EOT;

    // Prevent direct access to the page
    static function noDirectAccess() {
        //echo print_r(get_included_files());
        //echo "<br>";
        //echo count(get_included_files());
        // https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
        if(count(get_included_files()) == 2) {
            // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
            http_response_code(403);
            exit(self::DIRECT_ACCESS_ERROR);
        }
    }
}
?>