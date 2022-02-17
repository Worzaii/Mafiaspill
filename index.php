<?php

include 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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

$log->info('Completed, shutting down');
