<?php
include("core.php");
if(r1()){
  startpage("Full spiller informasjon");
  /*Starter scriptet*/
  if(isset($_POST['unavn'])){
    $u = $db->escape($_POST['unavn']);
    $uva = user_exists($u, 2);
    if($uva != false){
      /*
       * Brukeren eksisterer, henter fram all informasjon fra denne spilleren.
       * Følgende skal vises om brukeren:
       * Banklogg
       * Tilbudslogg
       * Ranklogg(krim/biltyveri/ranspiller/lotto)
       * Pengespill(Blackjack/Kastmynt)
       */
      echo '<table class="table" style="width:400px">
        <tr>
          <th colspan="2">Siste aktiviteter</th>
        </tr>';
      $krim = $db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id' AND `timelast` <> NULL ORDER BY `id` DESC LIMIT 0,1");
      if($db->num_rows() == 0){
        $kri1 = "Spilleren har ikke utført noen krim enda etter 'timelast' ble satt inn!";
      }
      else{
        $re = $db->fetch_object();
        $kri1 = '<span id="lastkrim"></span><script type="text/javascript">teller('.(time() - $re->timelast).',"lastkrim",false,"opp");</script>';
      }
      $bilt = $db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `time` > '$time' ORDER BY `id` DESC LIMIT 0,1");
      if($db->num_rows() == 0){
        $bil1 = "Spilleren har ikke utført noen biltyveri enda etter 'timelast' ble satt inn!";
      }
      else{
        $re2= $db->fetch_object();
        $bil1 = '<span id="lastbilt"></span><script type="text/javascript">teller('.(time() - $re2->timelast).',"lastbilt",false,"opp");</script>';
      }
      echo '<tr><td>Krim: <br />'.$kri1.'</td><td>Biltyveri: <br />'.$bil1.'</td></tr></table>';
    }
  }
  else{
    echo '
    <h1>Vis full brukeroversikt for valgt spiller</h1>
    <form method="post" action="">
      <table class="table">
        <tr>
          <td>Full spillerinformasjonsside</td>
        </tr>
        <tr>
          <td><input type="text" value id="usernavn" name="unavn"><input type="submit" value="Sjekk opp brukeren"></td>
        </tr>
      </table>
    </form>';
  }
}
else{
  noaccess();
}
endpage();