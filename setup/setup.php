<?php
/**
 * todo: Make a CLI script to create users fast. With options of status and more.
 */
    $user = readline("Brukernavn: ");
    $regex = preg_match("/^[a-z0-9-_]{3,10}$/i", $user);
    if($regex){
        echo "Valid username, you're amazing mate! <3";
    } else{
        echo "Didn't work! :(";
    }