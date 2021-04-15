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
    // Automatically sanitizes for SQL
    static function tryGet($key) {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    }

    // Exits with an error message if the query failed
    static function checkQuery($conn, $result) {
        if ($result === false) {
            $error = $conn->error;
            echo "<p>Query failed: $error.</p>";
            exit;
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