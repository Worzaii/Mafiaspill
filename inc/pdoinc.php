<?php
/* Setting up database for usage within the rest of the script scope */
try {
    $db = new PDO("mysql:dbname=" . DATABASE . ";host=" . HOST, USERNAME, PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false/*,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\ca.pem",
        PDO::MYSQL_ATTR_SSL_CERT => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-cert.pem",
        PDO::MYSQL_ATTR_SSL_KEY => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-key.pem"*/
    ]);
} catch (PDOException $PDOException) {
    if (php_sapi_name() != "cli") {
        error_log("Couldn't connect to database. Error: " . var_export($PDOException, true));
        die(json_encode(['string' => "Kunne ikke koble til db. ", 'state' => 0]));
    } else {
        die("Couldn't connect to the database... Error message:\n" . $PDOException->getMessage());
    }
}