<?php

if (isset($_SERVER['DEV']) and $_SERVER['DEV'] == 1) {
    phpinfo(-1);
} else {
    echo "<h1>Not available in a PROD env.</h1>";
}
