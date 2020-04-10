<?php
include "../inc/database.php";
include "../classes/User.php";
$user = new UserObject\User($db);
$werzy = $user->setUserID(1)->connect($db)->getUserObject();

var_dump($werzy);