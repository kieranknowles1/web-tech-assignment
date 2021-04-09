<?php
// https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
if(count(get_included_files()) ==1) {
    // https://stackoverflow.com/questions/5061675/emulate-a-403-error-page
    http_response_code(403);
    exit("Direct access not permitted.");
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
}
?>