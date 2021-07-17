<?php

use Game\GameHandler;

function AutoLoader($name)
{
    if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "$name" . '.php')) {
        include_once __DIR__ . DIRECTORY_SEPARATOR . "$name" . '.php';
    } else {
        throw new Exception("Class ($name) does not exist! \n" .
            'Trying to include the following: '.__DIR__ . DIRECTORY_SEPARATOR . "$name" . '.php');
    }
}

spl_autoload_register('AutoLoader');
$game = new GameHandler();
