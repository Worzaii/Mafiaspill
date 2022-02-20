<?php

namespace Classes;

/**
 * This class handles authorization, sessions and data from the game and applies the right roles through other classes.
 */
class Authorization
{
    public function __construct()
    {
        try {
            $this->startSession();
        } catch (\Exception $e) {
            die('An error has occurred: \n<b>' . $e->getMessage());
        }
        return $this;
    }

    private function startSession(): bool
    {
        if (session_status() == PHP_SESSION_DISABLED) {
            throw new \Exception('PHP cannot run with sessions, ' .
                'therefore an error has occurred! Please check your PHP config file.');
        } elseif (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return true;
    }

    private function handleSession()
    {
        $s = $_SESSION;
        if (!isset($s['uid'])) {
            /**
             * Unauthorized, show only accessible content for guests.
             */

        }
    }
}
