<?php
//Bunkerhandler
define("LVL",true);
include("../core.php");
header("Content-Type: text/json");
//Recieving data
$data = $_POST;
$row = $_POST['data'];
$user = $_POST['user'];
$db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($user)."'");
if($db->num_rows() == 1){
  $found = 1;
}
else{
  $found = 0;
}
echo json_encode(array("res"=>$found));