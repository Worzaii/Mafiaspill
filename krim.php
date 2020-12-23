<?php
/**
 * TODO: Make the new crime script working again, but better!
 */

use UserObject\Crime\Crime;

include("core.php");
include "classes/Crime.php";
$crime = new Crime($obj, $db);