<?php

/* Setting up database for usage within the rest of the script scope */
try {
    error_log("Connecting to db with these credentials: " . DATABASE . " " . HOST . " " . USERNAME . " " . PASSWORD);
    $db = new PDO(
        "mysql:dbname=" . DATABASE . ";host=" . HOST, USERNAME, PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
    );
} catch (PDOException $PDOException) {
    if (php_sapi_name() != "cli") {
        error_log("Couldn't connect to database. Error: " . var_export($PDOException, true));
        die(
        json_encode(
            [
                'string' => "Kunne ikke koble til db. " . $PDOException->getMessage(),
                'state' => 0
            ]
        )
        );
    } else {
        die("Couldn't connect to the database... Error message:\n" . $PDOException->getMessage());
    }
}
