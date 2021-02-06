<?php
include 'core.php';
include 'classes/Bank.php';
/** @var \UserObject\User $obj */
/** @var PDO $db */
$bank = new \UserObject\Bank($obj, $db);
