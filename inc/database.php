<?php
try {
    $db = new PDO("mysql:dbname=mafia;host=127.0.0.1", "mafia", "mafia", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\ca.pem",
        PDO::MYSQL_ATTR_SSL_CERT => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-cert.pem",
        PDO::MYSQL_ATTR_SSL_KEY => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-key.pem"
    ]);
} catch (PDOException $PDOException) {
    error_log("Couldn't connect to database. Error: " . $PDOException->getMessage());
    die("Kunne ikke koble til db!<br><a href=\"/index.php\">Tilbake til innlogging.</a>");
}