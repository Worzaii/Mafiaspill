<?php
$connection = "mysql:dbname=mafia;host=127.0.0.1";
$user = "mafia";
$pass = "mafia";
try {
    $con = new PDO(
        $connection,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    error_log("Connected to DB with PDO " . var_export($con, true));
} catch (PDOException $ex) {
    error_log("Couldn't connect with PDO: " . $ex->getMessage());
}
