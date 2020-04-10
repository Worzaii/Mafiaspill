<?php
if(strtolower(php_sapi_name()) == 'cli'){
    include "../inc/database.php";
    /**
     * TODO: Add checkers for different time-sensitive objectives like lottery. Every minute check or every second check? Every 15 seconds check?
     */
} else die();