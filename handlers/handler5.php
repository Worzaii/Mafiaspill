<?php
/*
 * Denne skal behandle inputs fra s&oslash;knadssenteret
*/
header('Content-type: application/json');
include("../core.php");
if(isset($_GET['id'])&& isset($_GET['way'])){
  /*Starter behandling av informasjon mottat av AJAX-foresp&oslash;rselen*/
  $id = $db->escape($_REQUEST['id']);
  $way = $db->escape($_REQUEST['way']);
  /*Validering*/
  if(!is_numeric($id) || !is_numeric($way)){
      /*Ikke gyldig input*/
      $res = array("res"=>0,"txt"=>'Det ble ikke oppgitt riktige verdier! Last inn siden p&aring; nytt og vote igjen!');
  }
  else{
    /*Legger inn vote om ikke det allerede er voted*/
    $s = $db->query("SELECT * FROM `vote` WHERE `uid` = '$obj->id' AND `sid` = '$id'");
    if($db->num_rows() == 1){

      /*Allerede voted, sjekker om det er motsatt vote*/
      $v = $db->fetch_object();
      if($way == 0){
        /*Nedstemt*/
        $db->query("UPDATE `vote` SET `res` = '0' WHERE `uid` = '$obj->id' AND `sid` = '$id' LIMIT 1");
        if($db->affected_rows() == 1){
          /*Resultat: Fullf&oslash;rt*/
          $res = array("res"=>1,"txt"=>'Du stemte ned!');
        }
        else{
          $res = array("res"=>0,"txt"=>'Du har allerede stemt ned!');
        }
      }
      else{
        /*Oppstemt*/
        $db->query("UPDATE `vote` SET `res` = '1' WHERE `uid` = '$obj->id' AND `sid` = '$id' LIMIT 1");
        if($db->affected_rows() == 1){
            /*Resultat: Fullf&oslash;rt*/
            $res = array("res"=>1,"txt"=>'Du stemte opp!');
        }

        else{
            $res = array("res"=>0,"txt"=>'Du har allerede stemt opp!');
        }
      }
    }
    else{ 
      /*Legger inn vote i db*/
      $db->query("INSERT INTO `vote`(`uid`,`sid`,`time`,`res`) VALUES('$obj->id','$id',UNIX_TIMESTAMP(),'$way')");
      $ret = ($way == 1) ? "opp" : "ned";
      $res = array("res"=>1,"txt"=>'Du har stemt '.$ret.'!');
    }
  }
}
else if(isset($_GET['msg']) && isset($_GET['kid'])){
  /*En kommentar blir sendt til serveren, validerer lengde*/
  $msg = $db->escape($_GET['msg']);
  $id = $db->escape($_GET['kid']);
  if(strlen($msg) <= 5){
    /*Melding for kort, minimum 6 tegn*/
    $res = array("res"=>0,"txt"=>'Din kommentar er for kort, den m&aring; v&aelig;re minst 6 tegn.',"slett"=>0);
  }
  else{
    $db->query("INSERT INTO `soknadkom`(`uid`,`sid`,`time`,`msg`) VALUES('$obj->id','$id',UNIX_TIMESTAMP(),'$msg')");
    $res = array("res"=>1,"txt"=>'Din kommentar har blitt lagret!',"slett"=>1);
  }
}
else{
    $res = array("res"=>0,"txt"=>'Forventet verdier, men fikk ingenting. Pr&oslash;v igjen!');
}
echo json_encode($res);
