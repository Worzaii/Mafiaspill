<?php

namespace Classes;

/**
 * Will handle all routes created as subdirectories and data sent back and forth
 * @example domain/News/1 will convert to domain/index.php?s=news&id=1
 * This means that Routing also will handle all routes defined in Pages directory as they're loaded sequentially.
 */
class RouterHandler
{

    public function __construct()
    {
        return $this;
    }
}