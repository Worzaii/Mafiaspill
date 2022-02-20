<?php

include 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Classes\Authorization;
use Classes\DatabaseConnectionSettings;
use Classes\RouterHandler;

$log = new Logger('mafiagame');
$log->pushHandler(new StreamHandler('logs/mafia_debug.log', Logger::DEBUG, false));
$log->info('Starting up');
/**
 * Implement the following:
 * Authentication
 * Routing
 * Page Addons depending on Auth
 * Automatic handlers?
 */

$log->info('Starting database authentication...');
$dcs = new DatabaseConnectionSettings(
    host: 'localhost',
    user: 'newmafia',
    pass: 'newmafia',
    database: 'newmafia',
    port: 3306
);
try {
    $d = new PDO($dcs->dns, $dcs->user, $dcs->pass);
} catch (PDOException $PDOException) {
    if ($PDOException->getCode() == 1049) {
        $log->critical('The database has not been created! ' .
            'Please consult the setup.php file!');
    } elseif ($PDOException->getCode() == 1045) {
        $log->critical('The user you are trying to authenticate with either does not have ' .
            'privileges to the database you are trying to connect to, ' .
            'is not created or otherwise is locked out. ' .
            'Please ensure that it has privileges by running the following command in your MySQL console: ' .
            'SHOW GRANTS FOR \'' . $dcs->user . '\'@\'' . $dcs->host . '\';');
    }
    $log->critical(
        'Could not connect to database! Error: ',
        $PDOException->errorInfo
    );
    die('Could not connect to database! Please check your logs for more info');
}
$log->info('Seems database is up and running, continuing...');

$log->debug('Starting Auth class.');
$auth = new Authorization();

$route = new RouterHandler();

$log->info('Completed, shutting down');
echo 'I got all the way to the bottom without error! <3';
