<?php

/**
 * TODO: Apparently, readline won't format color codes as wished. Might have to run it somwhow else instead.
 * Deeper dive is necessary to figure this out.
 */
readline("Answer this thing: \033[1m");
echo "Now, \033[1mTHIS\e[0m should be bold";
function ask_hidden($prompt)
{
    echo $prompt;

    echo "\033[30;40m";  // black text on black background
    $input = fgets(STDIN);
    echo "\033[0m";      // reset

    return rtrim($input, "\n");
}

$pass = ask_hidden('pass: ');
var_dump($pass);
