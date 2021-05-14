<?php

try {
    $db = new PDO(
        "mysql:dbname=" . DATABASE . ";host=" . HOST, "mafia", "mafia", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
    );
} catch (PDOException $PDOException) {
    error_log("Couldn't connect to database. Error: " . $PDOException->getMessage());
    die("Kunne ikke koble til db!<br><a href=\"/index.php\">Tilbake til innlogging.</a>");
}
