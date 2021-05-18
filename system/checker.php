<?php

if (strtolower(php_sapi_name()) == 'cli') {
    include "../inc/database.php";
    error_log("Starting scheduled script up.");
    /**
     * TODO: Add checkers for different time-sensitive objectives like lottery. Every minute check or every second check? Every 15 seconds check?
     */

    error_log("Shutting down scheduled script.");
} else {
    die();
}
