<?php


namespace Game;

use Mafia\Route\Route;

/**
 * Class GameHandler
 * @package Game
 */
class GameHandler
{
    public function __construct()
    {
        $r = new Route();
        Route::add("/", function () {
            echo "NANI?!";
        });
    }
}
