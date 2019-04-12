<?php
include("core.php");
startpage("Firma-paneler");
  if(fengsel() == true){
echo '
<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller('.fengsel(true).',\'krim\',true,\'ned\');
</script>
';
}
else if(bunker() == true){
$bu = bunker(true);
echo '
<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
<script>
teller('.($bu - time()).',\'bunker\',false,\'ned\');
</script>
';
}
else{
echo '<h1>Dine firmaer</h1>';
$config = array("type"=>array("1"=>"Lottofirma","2"=>"Flyplassfirma"));
//echo '<h1>Firmapaneler</h1><p class="feil">Dette gjelder for de med flyplassfirmaer:<br>Heisann! Werzaire her. Jeg vil informere dere om at innstillinger for flyplass ikke er helt klart enda. Og derfor &oslash;nsker jeg at dere lar alle innstillingene som st&aring;r tilgjengelig la v&aelig;re st&aring;ende da det kan v&aelig;re feil med noe. Jeg har ikke testet dette selv. Det vil bli oppdatert om ikke s&aring; lenge. Man f&aring;r ikke noe p&aring; flyplassene enda, s&aring; det vil ogs&aring; komme s&aring; snart jeg har f&aring;tt scriptet ferdig for flyplasseierne!<br>~ Werzaire</p>';
$s = $db->query("SELECT * FROM `firma` WHERE `Eier` = '{$obj->id}' ORDER BY `id` ASC");//Henter firmapanelene til spilleren.
if($db->num_rows() >= 1 || $obj->status == 1){
  if($obj->status == 1)$s = $db->query("SELECT * FROM `firma` ORDER BY `id` ASC");
  if(isset($_POST['allout']) || isset($_POST['overfor'])){
    if(isset($_POST['allout'])){
      /*Tar ut pengene s&aring; lengst personen eier firmaet. Admin kan ikke ta ut.*/
      $id = $db->escape($_POST['id']);
      $in = firma($id);
      $eier = $in[1];
      if($eier == $obj->id){
        /*Er eieren...*/
        $db->query("UPDATE `users` SET `hand` = (`hand` + ".$in[3].") WHERE `id` = '{$eier}' LIMIT 1");
        $db->query("UPDATE `firma` SET `Konto` = '0' WHERE `id` = '$id' LIMIT 1");
        echo '<p class="lykket">Du tok ut '.number_format($in[3]).' kr!</p>';
      }
      else{
        echo '<p class="feil">Dette er ikke ditt firma!</p>';
      }
    }
    else if(isset($_POST['overfor']) && isset($_POST['overfortil'])){
      $usr = $_POST['overfortil'];
      $id = $db->escape($_POST['id']);
      $u = (user_exists($usr) == true) ? true : false;
      if($u == true){
        /*Overf&oslash;rer*/
        $uin = user_exists($usr,1);
        if(is_numeric($uin)){
          if($db->query("UPDATE `firma` SET `Eier` = '{$uin}' WHERE `id` = '$id' LIMIT 1")){
            echo '<p class="lykket">Du har overf&oslash;rt firmaet ditt til '.user($uin).'!</p>';
            sysmel($uin, '<b>--Firma</b><br>Du har mottat et firma ifra '.user($obj->id).'! Klikk her for &aring; se ditt panel over firmaene dine: <a href="http://mafia-no.net/Firmaer">Firmapanel</a>');
          }
          else{
            echo '<p class="feil">Feil i sp&oslash;rring! '.$db->last_error.'</p>';
          }
        }
      }
      else{
        echo '<p class="feil">Brukeren eksisterer ikke!</p>';
      }
    }
  }
  while($a = mysqli_fetch_object($s)){
    if($a->Type == 1){
      /*Lottofirma har ekstra egenskaper*/
      /*Noen variabler for Lotto*/
      $lottoupdate='<input type="submit" name="changelotto" value="Oppdater Lottoinnstillinger."><br>';
      /*END VARIABLER*/
      $get_settings = $db->query("SELECT * FROM `lottoconfig` ORDER BY `id` DESC LIMIT 1");
      if($db->num_rows() == 1){
        $val = $db->fetch_object();
        $current_value1 = $val->Loddpris;
        $current_value2 = $val->Tid;
        $current_value3 = $val->Prosent;
        $current_value4 = $val->Antlodd;
      }
      else{
        $current_value1 = NULL;
        $current_value2 = NULL;
        $current_value3 = NULL;
        $current_value4 = NULL;
      }
      if(isset($_POST['changelotto']) && (isset($_POST['edit1lotto']) && isset($_POST['edit2lotto']) && isset($_POST['edit3lotto']) && isset($_POST['edit4lotto']))){
        /*Endrer verdier om de blir godkjente*/
        $v1 = $db->escape($_POST['edit1lotto']);
        $v2 = $db->escape($_POST['edit2lotto']);
        $v3 = $db->escape($_POST['edit3lotto']);
        $v4 = $db->escape($_POST['edit4lotto']);
        if($v1 == $current_value1 && $v2 == $current_value2 && $v3 == $current_value3 && $v4 == $current_value4){
                echo '<p class="feil">Verdiene er allerede oppdatert, pr&oslash;v heller &aring; endre den f&oslash;r du lagrer! =)</p>';
        }
        else{/*Endrer*/
          if(($v1 >= 10000 && $v1 <= 1000000) && ($v2 >= 10 && $v2 <= 60) && ($v3 >= 5 && $v3 <= 30) && ($v4 >= 100 && $v4 <= 1000)){
            /*Alt er ok, oppdaterer*/
            if($db->query("INSERT INTO `lottoconfig`(`Loddpris`,`Tid`,`Prosent`,`Antlodd`) VALUES('$v1','$v2','$v3','$v4')")){
              echo '<p class="lykket">Du har oppdatert instillingene for lotto, de vil bli aktive ved neste runde.</p>';
              $current_value1 = $v1;
              $current_value2 = $v2;
              $current_value3 = $v3;
              $current_value4 = $v4;
            }
            else{
                echo '<p class="feil">Det oppstod en feil ved oppdatering, ta kontakt med Werzaire og gi han dette: "' . mysqli_error($db->con) . '"</p>';
            }
          }
          else{
            /*Noe stemte ikke, sier ifra*/
            if($v1 >= 10000 && $v1 <= 1000000){echo '<p class="feil">Tallet m&aring; v&aelig;re mellom 10,000kr-1,000,000kr p&aring; loddprisen!</p>';}
            if($v2 >= 10 && $v2 <= 60){echo '<p class="feil">Tallet m&aring; v&aelig;re mellom 10 til 60 minutter p&aring; tiden!</p>';}
            if($v3 >= 5 && $v3 <= 30){echo '<p class="feil">Tallet m&aring; v&aelig;re mellom 5%-15% p&aring; prosenten som trekkes av til firmakonto!</p>';}
            if($v4 >= 100 && $v4 <= 1000){echo '<p class="feil">Tallet m&aring; v&aelig;re mellom 100-1,000 stk p&aring; antall lodd spillere kan kj&oslash;pe samtidig!</p>';}
          }
        }
      }
      $ex = '
      <tr>
        <td>Pris per lodd:</td><td><input name="edit1lotto" type="number" min="10000" max="1000000" value="'.$current_value1.'"> kr<br>Minst: 10,000kr Maks: 1,000,000kr</td>
      </tr>
      <tr>
        <td>Tid per lottorunde:</td><td><input name="edit2lotto" type="number" min="10" max="60" value="'.$current_value2.'"> minutter<br>Minst: 10 minutter Maks 1 time(60 minutter)</td>
      </tr>
      <tr>
        <td>Trekk til deg:</td><td><input name="edit3lotto" type="number" min="5" max="15" value="'.$current_value3.'"> % trekk<br>Minst: 5% Maks: 30%</td>
      </tr>
      <tr>
        <td>Antall mulige lodd:</td><td><input name="edit4lotto" type="number" min="100" max="1000" value="'.$current_value4.'"> stk<br>Minst: 100 lodd Maks: 1,000 lodd</td>
      </tr>
      ';
      $by = NULL;
    }
    else{
      $ex = null;
      $by = "en i ".city($a->By);
      $lottoupdate = NULL;
    }
    echo '<form method="post" action="">
    <table class="table">
      <thead>
        <tr><th colspan="2">Type: '.$config['type'][$a->Type].'&raquo;<span title="Flyplassens navn">'.htmlentities($a->Navn)."</span>".$by.'</th></tr>
      </thead>
      <tbody>
      <tr>
        <td>Eier</td><td>'.status($a->Eier,1).'</td>
      </tr>
      <tr>
        <td>Innsamlet:</td><td>'.number_format($a->Konto).' kr</td>
      </tr>
      '.$ex.'
      <tr>
        <th colspan="2">Valg for eier:</th>
      </tr>
      <tr>
        <td colspan="2">
        '.$lottoupdate.'
        <input type="submit" name="allout" value="Ta ut alle pengene!"><br>
        <input type="submit" value="Overf&oslash;r til en annen spiller:" name="overfor">
        <input type="text" placeholder="Nick" name="overfortil"></td>
      </tr>
      </tbody>
    </table>
    <input type="hidden" value="'.$a->id.'" name="id">
    </form>';
  }
}
else{
  echo '<p>Du eier ingen firmapaneler!</p>';
}
}
endpage();
?>