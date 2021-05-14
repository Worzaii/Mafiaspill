<?php

function genpass()
{
    $pass_chars = "abcdefghijklmnopqrstuvwxyz0123456789-_.!\"#%&\\/()=?";
    $pass = "";
    for ($i = 0; $i < 12; $i++) {
        $pass .= $pass_chars[rand(0, strlen($pass_chars) - 1)];
    }
    return $pass;
}
