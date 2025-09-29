<?php

if (isset($_SERVER['DEV']) && $_SERVER['DEV'] == 1) {
    phpinfo();
} else {
    header("Location: /");
}
