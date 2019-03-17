<?php
  include 'core.php';
//  if(!r1()){
//    startpage("Ikke tilgjengelig");
//    noaccess();
//    endpage();
//  }
  $style="
    <style type=\"text/css\">
    ul li.item{display:inline;list-style-type:none;}
    div.holder ul{text-align:center;list-style-type:none;}
    div.holder ul li{display:inline;padding:0 10px 0 10px}
    </style>
  ";
  if(fengsel() == true){
    startpage("Svartebørsen");
    echo '
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">'.fengsel(true).'</span></p>
    <script type= "text/javascript">
    teller('.fengsel(true).',\'krim\',true,\'ned\');
    </script>
    ';
  }
  else if(bunker() == true){
    startpage("Svartebørsen");
    $bu = bunker(true);
    feil('Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br />Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
    <script type="text/javascript">
    teller('.($bu - time()).',\'bunker\',false,\'ned\');
    </script>
    ');
  }
  else{
    if(isset($_GET['pa'])){
      $p = $_GET['pa'];
      if($p == 1){
        /*Bunkermarked*/
            // Laget av Thomas 15.03.2014
            // Redigert av Nicholas 19.03.2014
            startpage("Bunkermarked");
            echo '<h1>Kjøp Eiendom</h1><p><a href="/Marked">Tilbake!</a></p>';
            $eiendommer = array(
             1=>array('navn' => "Pappeske", 'places' => 0, 'id' => 1, 'price' => 1000000),
             2=>array('navn' => "Telt", 'places' => 0, 'id' => 2, 'price' => 5000000),
             3=>array('navn' => "Campingvogn", 'places' => 2,'id' => 3, 'price' => 10000000),
             4=>array('navn' => "Liten leilighet med 1 soverom", 'places' => 4,'id' => 4, 'price' => 15000000),
             5=>array('navn' => "Leilighet med 3 soverom", 'places' => 6,'id' => 5, 'price' => 30000000),
             6=>array('navn' => "Hus med 3 soverom og kjeller", 'places' => 8, 'id' => 6, 'price' => 45000000),
             7=>array('navn' => "Palass", 'places' => 10, 'id' => 7, 'price' => 60000000),
             8=>array('navn' => "Slott", 'places' => 12, 'id' => 8, 'price' => 100000000)
            );
            $plus = $obj->eigendom + 1;
            $eigendom = $eiendommer[$obj->eigendom]['id'];
            if($_POST['eigendoms']){
              if($obj->eigendom >= 8){
                $res = '<p class="feil">Du har allerede beste eiendom.</p>';
              }
              else{
                if($obj->hand >= $eiendommer[$plus]['price']){
                  $db->query("UPDATE `users` SET `eigendom` = (`eigendom` + 1),`hand`=(`hand` - ".$eiendommer[$plus]['price'].") WHERE `id` = '".$obj->id."' LIMIT 1") or die(mysqli_error($db->connection_id)."feil($plus): ".$db->last_query);
                  $res= '<p class="lykket">Du kjøpte '.$eiendommer[$plus]['navn'].'</p>';
                  $obj->eigendom = $plus;
                }
                else{
                  $res= '<p class="feil">Du har enten ikke råd til å kjøpe en bedre eiendom, ellers så har du ikke nok penger ute på handa!</p>';
                }
              }
            }
            if(isset($res)){echo $res;}
            $knapp = ($obj->eigendom >= 8) ? 'style="display:none;' : NULL;
            $duhar = ($obj->eigendom != 0) ? $eiendommer[$obj->eigendom]['navn'] : "ingen eiendom.";
            echo '<p>Du har '.$duhar.'</p>';
        ?>
  <form action="" method="post">
    <table class="table">
        <th colspan="3">Eiendommer</th>
        <tr>
          <td>Eiendom</td><td>Bunker Plasser</td><td>Kostnad</td>
        </tr>
        <?php
        foreach($eiendommer as $eigendom => $hus){
          $style = ($obj->eigendom == $hus['id']) ? 'style=\"background-color:green;color:green;\"' : NULL;
          echo '<tr><td '.$style.'>'.$hus['navn'].'</td><td '.$style.'>'.$hus['places'].'</td><td>'.number_format($hus['price']).'</td></tr>';
        }
        ?>
    </table>
  </form>
  <form action="" method="post">
    <input type="submit" <?=$knapp?> value="Kjøp Neste Eiendom!" name="eigendoms"/>
  </form>
      <?php
      }
      else if($p == 2){
        /*Våpenkjøp*/
        startpage("Våpenkjøp");
        echo '<h1>Våpenbutikk</h1><p><a href="/Marked">Tilbake!</a></p><p>Ditt gjeldende våpen:<br>'.weapon($activeweapon).'</p>';
        if(isset($_POST['vapen'])){
          $id = $db->escape($_POST['vapen']);
          $db->query("SELECT * FROM `weapons` WHERE `id` = '$id'");
          if($db->num_rows() == 1){
            $w = $db->fetch_object();
            if($obj->hand >= $w->price){
              var_dump($wea);
              $wea[$id]=array(1=>1,2=>0);
              /*for($i=0;$i<=9;$i++){

                if($wea[$i][1]== 1){
                  $wea[$i][1]=
                }
              }*/
              $nywep = serialize($wea);
              $db->query("UPDATE `users` SET `weapon` = '$nywep',`hand` = (`hand` - ".$w->price.") WHERE `id` = '{$obj->id}'");
              if($db->affected_rows() == 1){
                lykket('Våpenet ble kjøpt!');
              }
              else{
                feil('Kunne ikke kjøpe våpenet: '.mysqli_error($db->connection_id).'');
              }
            }
            else{
              feil('Du har ikke nok penger på handa til å kjøpe dette våpenet!');
            }
          }
          else{
            feil('Dette våpenet finnes ikke!');
          }
        }
        else if(isset($_POST['bruk'])){
          $id = $db->escape($_POST['bruk']);
          $db->query("SELECT * FROM `weapons` WHERE `id` = '$id'");
          if($db->num_rows() == 1){
            $w = $db->fetch_object();
              $wea[$id]=array(1=>1,2=>1);
              $nywep = serialize($wea);
              $db->query("UPDATE `users` SET `weapon` = '$nywep' WHERE `id` = '{$obj->id}'");
              if($db->affected_rows() == 1){
                lykket('Du tok i bruk våpenet ditt!');
              }
          }
          else{
            feil('Våpenet finnes ikke!');
          }
        }
        #a[Våpennr][1=Kjøpt,2=IBruk]
        $a = array(
          0=>array(1=>1,2=>1),/*Standard(ingen våpen*/
          1=>array(1=>0,2=>0),
          2=>array(1=>0,2=>0),
          3=>array(1=>0,2=>0),
          4=>array(1=>0,2=>0),
          5=>array(1=>0,2=>0),
          6=>array(1=>0,2=>0),
          7=>array(1=>0,2=>0),
          8=>array(1=>0,2=>0),
          9=>array(1=>0,2=>0)
        );
        $wp = $db->query("SELECT * FROM `weapons` ORDER BY `id` ASC");
        echo '<form method="post" action="Marked?pa=2"><table class="table" style="width:200px;margin:0 auto;"><tr><td style="text-align:center;">*</td><td>Våpen</td><td>Pris</td></tr>';
        $i=1;
        while($r = mysqli_fetch_object($wp)){
          /*Lister opp alle kjøpelige våpen*/
          if($wea[$i][1]){
            echo '<tr class=""><td><input type="radio" name="bruk" value="'.$r->id.'"></td><td>'.weapon($i).'</td><td style="color:#0f0;">Kjøpt</td></tr>';
          }
          else{
            echo '<tr class=""><td><input type="radio" name="vapen" value="'.$r->id.'"></td><td>'.weapon($i).'</td><td>'.number_format($r->price).'</td></tr>';
          }
          $i++;
        }
          echo '<tr><td colspan="3" style="text-align:center;"><input type="submit" value="Kjøp våpenet!"><input type="submit" value="Velg våpenet"></td></tr>';
          echo '</table></form>';
      }
      else if($p == 3){
        /*Kulebutikk*/
        startpage("Kjøp kuler!");
        ?>
<h1>Kjøp kuler i <?=city($obj->city);?></h1>
<p><a href="/Marked"><span style="font-size:20px;">&larr;</span> Tilbake til Svartebørsen</a></p><?php

        $query = $db->query("SELECT * FROM `ammo` WHERE `active` = '1' AND `city` = '{$obj->city}'");
        if($db->num_rows($query) == 0){
          feil('Det er ingen kuler tilgjengelig. Sjekk igjen senere.');
        }
        else{
          // Det er kuler tilgjengelig. Tegn tabell.
          $fetch = $db->fetch_object($query);

          if(isset($_POST['kjop'])){
            $antall = $db->escape($_POST['antall']);
            if($antall > $fetch->kuler_left){
              feil('Det er ikke så mange kuler tilgjengelig i '.  city($obj->city, 1).'. Prøv en annen by eller hør med familiene om de produserer kuler.</p>');
            }
            elseif($fetch->kuler_pris > $obj->hand){
              feil('Du har ikke nok penger ute på hånden!');
            }
            elseif($antall <= 0){
              feil('Ugyldig Input.');
              }else if($antall <= $fetch->kuler_left && $obj->hand >= ($antall * $fetch->kuler_pris) && $antall >= 1){
              $db->query("UPDATE `users` SET `bullets` = (`bullets` + '$antall'), `hand` = (`hand` - ".($fetch->kuler_pris * $antall).") WHERE `id` = '$obj->id' LIMIT 1");
              $db->query("UPDATE `ammo` SET `kuler_left` = (`kuler_left` - '$antall') WHERE `active` = '1'");
              $db->query("INSERT INTO `kulelogg`(`uid`,`kuler`,`time`,`pris`) VALUES('{$obj->id}','$antall',UNIX_TIMESTAMP(),'".($antall*$fetch->kuler_pris)."')");
              lykket('Du kjøpte '.$antall.' kuler for '.number_format($fetch->kuler_pris * $antall).'!');
            }
          }
          ?>
  <form action="" method="post">
      <table style="width:250px;" class="table">
          <th colspan="4">Kjøp kuler!</th>
          <tr>
              <td>Antall</td>
              <td>Pris</td>
              <td>Kuler igjen</td>
              <td>Valg</td>
          </tr>
          <tr>
              <td><input type="text" name="antall"/></td>
              <td><?=number_format($fetch->kuler_pris)?></td>
              <td><?=$fetch->kuler_left?></td>
              <td><input style="margin:5px;" value="Kjøp kuler" type="submit" name="kjop"/></td>
          </tr>
      </table>
  </form>
                          <?php
                      }
      }
      elseif($p == 4){
        if(!r1() || !r2()){
          startpage("Ikke åpent enda.");
          noaccess();
          endpage();
          die();
        }
        startpage("Våpenkjøp");
        $id = $db->escape($_POST['vappen']);
        $vapen = array(
          1=>array('navn' => "Colt", 'pris' => 84200, 'power' => 1),
          2=>array('navn' => "Glock 64", 'pris' => 147400, 'power' => 2),
          3=>array('navn' => "Dual Berettas", 'pris' => 294800, 'power' => 3),
          4=>array('navn' => "Desert Eagle", 'pris' => 874200, 'power' => 4),
          5=>array('navn' => "MP5", 'pris' => 1623000, 'power' => 5),
          6=>array('navn' => "PP Bizon", 'pris' => 4125000, 'power' => 6),
          7=>array('navn' => "P90", 'pris' => 8250000, 'power' => 7),
          8=>array('navn' => "AK-47", 'pris' => 16500000, 'power' => 8),
          9=>array('navn' => "M4A4", 'pris' => 33000000, 'power' => 9),
          10=>array('navn' => "Magnum Sniper Rifle", 'pris' => 66000000, 'power' => 10)
        );
        if(isset($_POST['kjop'])){
            $kjopet = $obj->weapon + 1;
              if($obj->hand < $kjopet && $obj->hand >= $vapen[$kjopet]['pris']){
                feil('Du har ikke råd til dette våpnet.');
            }elseif($obj->weapon >= '10'){
                feil('Du har allerede beste våpen.');
            }else{

            $prisen = $vapen[$kjopet]['pris'];
            if($obj->weapon == 0 || $obj->weapon == NULL){
                $db->query("UPDATE `users` SET `weapon` = '1', `hand` = (`hand` - $prisen) WHERE `id` = '$obj->id' LIMIT 1");
                lykket('Gratulerer. Du kjøpte ditt første våpen!');
            }elseif($obj->weapon != NULL || $obj->weapon != 0){
                $db->query("UPDATE `users` SET `weapon` = (`weapon` + 1),`hand` = (`hand` - $prisen) WHERE `id` = '$obj->id' LIMIT 1");

                lykket('Du kjøpte '.$vapen[$kjopet]['navn'].'');
            }
        }
      }
      ?>
  <a href="/Marked">Tilbake</a>
  <form action="" method="post">
      <table style="width:290px;" class="table">
          <th colspan="2">Kjøp våpen</th>
          <tr>
              <td>Navn</td>
              <td>Pris</td>
          </tr>
              <?php
              foreach($vapen as $vapen => $weapon){
                  $style = ($obj->weapon == $weapon['power']) ? 'style="color:green;"' : NULL;
                  echo '<tr><td '.$style.'>'.$weapon['navn'].'</td><td '.$style.'>'.number_format($weapon['pris']).' kr</td></tr>';
              }
              ?>
          <tr><td colspan="2"><input type="submit" name="kjop" value="Kjøp neste våpen"/></td></tr>
      </table>
  </form>
                <?php
        }
        // Slutt på Vappen
      else{
        /*Viser innholdsvisning*/
        header("Location: /Marked");
      }
    }
    else{/*Hvis ikke side er satt*/
      startpage("Svartebørsen",$style);
      echo '
      <h1>Svartebørsen</h1>
      <div class="holder" style="text-align:center;">
        <ul>
          <li><a style="button" href="?pa=1"><img src="" alt="Bunker" style="display: none;" />Bunker</a></li>
          <li><a style="button" href="?pa=4"><img src="" alt="Våpenshop" style="display: none;" />Kjøp Våpen</a></li>
          <li><a style="button" href="?pa=3"><img src="" alt="Ammo" style="display: none;" />Kjøp Kuler</a></li>
        </ul>
      </div>';
    }
  }
	endpage();  