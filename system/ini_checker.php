<?php

if (php_sapi_name() != "cli") {
    ob_start("nl2br");
}
echo "This file checks the PHP.ini settings for DEVELOPMENT environment\n";
echo "Checking if you're using the same hostname as cookie name:\n";
echo (ini_get("session.cookie_domain") != $_SERVER['HTTP_HOST']) ? "You're not!!! PHP.ini value is \"" . ini_get(
        "session.cookie_domain"
    ) . "\" and hostname is: \"" . $_SERVER['HTTP_HOST'] . "\"\n\n" : "Domain is equal to hostname! Awesome!\n\n";
echo "Your error log logs to: " . ini_get("error_log") . "\n";
echo "Showing errors? display_errors: " . ini_get("display_errors") . " (Recommend: 1)\n";
echo "Showing startup errors? display_startup_errors: " . ini_get("display_startup_errors") . " (Recommend: 1)\n";
echo "Logging errors? log_errors: " . ini_get("log_errors") . " (Recommend: 1)\n";
echo "Default charset: default_charset: " . ini_get("default_charset") . " (Recommend: UTF-8)\n";
echo "Loaded extensions: \n" . print_r(get_loaded_extensions(), true) . "\n";
echo "short_open_tag = " . ini_get("short_open_tag") . " (Recommend: 1)\n";
echo "Those were all. If all values are met, you're probably ready to go!";
print_r($_SERVER);
if (php_sapi_name() != "cli") {
    ob_end_flush();
}
