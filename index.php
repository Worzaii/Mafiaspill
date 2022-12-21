<?php

define('BASEPATH', true);
require_once('system/config.php');
if (isset($_SESSION['sessionzar'])) {
    header("Location: /nyheter.php");
} elseif (isset($_SESSION['grunn'])) {
    $grunn = $_SESSION['grunn'];
    unset($_SESSION['grunn']);
} else {
    $grunn = null;
}
echo "hello world";
?>
