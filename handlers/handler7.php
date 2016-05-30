<?php
define("NOUPDATE", true);
include '../core.php';
header('Content-type: application/json');
$dagpremie=array(1=>1000000,2=>1200000,3=>1400000,4=>1600000,5=>2000000,6=>3000000,7=>4000000,8=>5000000,9=>6000000,10=>7000000,11=>8000000,12=>9000000,13=>10000000,14=>11000000,15=>12000000,16=>13000000,17=>14000000,18=>15000000,19=>16000000,20=>17000000,21=>18000000,22=>19000000,23=>25000000,24=>50000000);
function goclean($txt){
  $txt = str_replace("å", "&aring;", $txt);
  $txt = str_replace("ø", "&oslash;", $txt);
  $txt = str_replace("æ", "&aelig;", $txt);
  return $txt;
}
if(isset($_POST['dag'])){
  $dag = $_POST['dag'];
  if(is_numeric($dag)){
    if(date("j") < $dag && date("m") == 12 && ($dag < 24)){
      $res = "Tilbudet finnes ikke, eller gjelder ikke idag!";
      $wiit=2;
    }
    else{
      $db->query("SELECT * FROM `offers` WHERE `gained` = '1' AND `uid` = '$obj->id' AND `day` = '".$db->escape($dag)."' LIMIT 1");
      if($db->num_rows() == 1){
        $res = "Du har allerede tatt deg i bruk av dette tilbudet!";
        $wiit=3;
      }
      else if($db->num_rows() == 0){
        $db->query("INSERT INTO `offers`(`uid`,`gained`,`day`) VALUES('$obj->id','1','$dag')");
        if($db->affected_rows() == 1){
          $res = "Du har mottat en gave på ".number_format($dagpremie[$dag])."kr!";
          $wiit=4;
          $db->query("UPDATE `users` SET `hand` = (`hand` + ".$dagpremie[$dag].") WHERE `id` = '$obj->id'");
        }
        else{

        }
      }
    }
  }
  else{
    $res = "Beklager, men det virker ikke riktig!";
    $wiit=1;
  }
}
else{
  $res = "Dag har ikke blitt definert!";
  $wiit=0;
}
print(json_encode(array("txt"=>"<p class='lykket'>".goclean($res)."</p>","wit"=>$wiit)));