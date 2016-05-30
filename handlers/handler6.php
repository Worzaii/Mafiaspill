<?php
/*Handlerfil for oppdateringer*/
header('Content-type: application/json');
define("NOUPDATE", 1);
include("../core.php");
$new_notice = 0;
$m = $db->query("SELECT * FROM `mail2` WHERE `tid` = '{$obj->id}' AND `read` = '0' AND `slettet` = '0' ORDER BY `id` DESC");
$id = mysqli_fetch_object($m);
if($db->num_rows() >= 1){
  $new_notice+=$db->num_rows();
}
$sm= $db->query("SELECT * FROM `sysmail` WHERE `uid` = '{$obj->id}' AND `read` = '0'");
if($db->num_rows() >= 1){
  $new_notice+=$db->num_rows();
}
if($new_notice >= 1){
    if($new_notice == 1){
        $tt = "Du har ny melding i innboksen.";
    }
    else{
        $tt = "Du har $new_notice nye meldinger!";
    }
  $txt = $tt."<br /><a style='color:rgba(107, 107, 225, 1);' href='http://mafia-no.net/Innboks'>Se Innboks!</a>";
  $show=1;
}
else{
  $txt = "Ingen nye varsler!";
  $show=0;
}
print(json_encode(array("txt"=>$txt,"show"=>$show,"last"=>$new_notice,"lastid"=>$id->id)));