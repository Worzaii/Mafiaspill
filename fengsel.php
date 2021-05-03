<?php

use UserObject\Jail;

/** @var \UserObject\User $obj */
/** @var PDO $db */
include("core.php");
include "classes/Jail.php";
$jail = new Jail($obj, $db, "Fengsel");
