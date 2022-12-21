<?php

include 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('mafiacron');
$log->pushHandler(new StreamHandler('logs/mafia_cron.log', Logger::DEBUG, false));
$log->info('Started to run...');
$log->info('Sending mail to all users...');
$headers = "From: henvendelser@localhost.localdomain\r\n";
$headers .= "Reply-To: henvendelser@localhost.localdomain\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$res = mail(
    'user1@localhost.localdomain',
    'This is a scheduled test!',
    '<h1>Thanks! </h1><p>for listening in on this automated mail! It has zero function for now :p</p>',
    $headers
);
if (!$res) {
    $log->error('Could not send mail! :(');
}
echo 'I am currently running a scheduled task!';

$log->info('Completed running cronjob.');
