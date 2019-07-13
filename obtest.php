<?php
include './core.php';
function replace_content($buffer)
{
    return str_replace(["Yes", "##CONTENT##"], ["Page title", "<h1>Hi there!</h1><p>I got replaced by ob!</p>"],
        $buffer);
}

ob_start("replace_content");
startpage("Yes");
echo "##CONTENT##";
endpage();
ob_flush();
