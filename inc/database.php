<?php
try {
    $db = new Pdo\Mysql("mysql:dbname=" . DATABASE . ";host=" . HOST, "mafia", "mafia");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
} catch (PDOException $PDOException) {
    error_log("Couldn't connect to database. Error: " . $PDOException->getMessage());
    die("Kunne ikke koble til db!<br><a href=\"/index.php\">Tilbake til innlogging.</a>");
}