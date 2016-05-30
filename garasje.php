<?php
include("core.php");
include("pagination.php");
startpage("Garasje");
echo '<img src="imgs/garasje.png" />';
	if(bunker() == true){
		$bu = bunker(true);
		echo '<h1>Garasjen</h1>
		<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br />Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
		<script type="text/javascript">
		teller('.($bu - time()).',\'bunker\',false,\'ned\');
		</script>
		';
	}
	else if(fengsel()){
		$ja = fengsel(true);
		echo '<h1>Garasjen</h1>
		<p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">'.$ja.'</span></p>
		<script type="text/javascript">
		teller('.$ja.',\'krim\',true,\'ned\');
		</script>
		';
	}
	else{
?>
<h1>Garasjen</h1>
<p>Velkommen til garasjen din!<br>Her for å selge biler, så må du sende bilene til en annen by, når de er fremme, dra til byen du sendte de til, og selg dem for penger :)</p>
<?php
    if((isset($_POST['selgbil']) || isset($_POST['sendbil']) || isset($_POST['sendtil']) || isset($_POST['selgalle'])) && isset($_POST['checkbil'])){
      //Utfører en av tre oppgaver
      include_once("inc/bilconfig.php");
      $biler = $_POST['checkbil'];/*Ikke bruk escape her, da den ødelegger data*/
      if(isset($_POST['selgbil'])){
        /*Åpner mulighet til å selge flere biler samtidig*/
        $errors = array("num"=>0,"res"=>"<p class=\"feil\">Kunne ikke selge valgte biler");
        $success = array("num"=>0,"sum"=>0,"res"=>NULL);
        foreach ($biler as $bil) {
          $bil = $db->escape($bil);
          $gc = NULL;
          $gc = $db->query("SELECT * FROM `bilgarasje` WHERE `id` = '{$bil}' AND `uid` = '{$obj->id}'");
          if($db->num_rows() == 1){
            $f = $db->fetch_object();
            if($obj->city == $f->curcity){//Spiller i samme byen
              if($obj->city == $f->gotcity){//Bil kan ikke selges i byen den ble stjålet.
                $errors["num"] +=1;
                $errors["res"] .=', fordi bilen var i samme by som du stjal den i';
              }
              else{//Selger bilen.
                $time = time();
                $pris = $prizes[$f->bilid];
                $s = $db->query("SELECT * FROM `bilgarasje` WHERE `uid` = '{$obj->id}' AND `lostfound` = '0' AND `transfer` <> '0' AND `transfer` < '{$time}' AND `id` = '{$bil}' AND `sold` = '0' AND `gotcity` <> `curcity` ORDER BY `id` DESC");
                if($db->num_rows() == 1){//Bilen eksisterer
                  $db->query("UPDATE `bilgarasje` SET `sold` = '1' WHERE `uid` = '{$obj->id}' AND `id` = '{$bil}'");
                  $success['num'] +=1;
                  $success['sum'] +=$pris;
                }
                else{
                  $errors['res'] .= ', fordi bilen ikke eksisterer';
                }
              }
            }
            else{
              $errors['res'].=', fordi du ikke er i samme by som bilen';
              $errors['num']+=1;
            }
          }//Bil eksisterer
          else{
            $errors['res'].=', fordi den ikke finnes, eller ikke tilhører deg';
            $errors['num']+=1;
          }
        }/*Foreach for flere biler END*/
        $errors['res'].='</p>';
        if($success['num'] >= 1){/*Kommer med successtekst og resultat*/
          $ant = ($success >= 2)?" biler":" bil";
          echo '<p class="lykket">Du solgte '.$success['num'].$ant.' til verdien av '.number_format($success["sum"]).'kr!</p>';
          $db->query("UPDATE `users` SET `hand` = (`hand` + ".$success['sum'].") WHERE `id` = '{$obj->id}' LIMIT 1");
        }
        if($errors['num'] >= 1){
          $ant = ($success >= 2)?" bilene dine":" bilen din";
          echo 'Det oppstod '.$errors["num"].' feil'.' da du prøvde å selge '.$ant.'!';
        }
      /*Selg bil END*/}
      else if(isset($_POST['sendbil'])){
        $tilby = $db->escape($_POST['tilby']);
        $errors = array("num"=>0,"res"=>"Noen biler ble ikke sendt.");
        $success = array("num"=>0,"res"=>NULL);
        foreach ($biler as $bil) {
          $bil = $db->escape($bil);
          if(is_numeric($tilby)){
            $tras = time() + 1800;//Tid før overførsel til annen by.
            $gc = $db->query("SELECT * FROM `bilgarasje` WHERE `id` = '{$bil}' AND `uid` = '{$obj->id}' AND `sold` = '0'")or die(mysql_error());
            if($db->num_rows() == 1){
              $f = $db->fetch_object($gc);
              if($f->transfer > time()){
                $errors["num"]+=1;
                $errors["res"].=", fordi valgte bil(er) allerede sendes til en by";
              }
              else{
                if($f->curcity == $tilby){
                  $errors['num']+=1;
                  $errors['res'].=', fordi bilen ikke kan sendes tilbake til byen den ble stjålet i';
                }
                else{
                  if($f->curcity == $obj->city){
                    if($db->query("UPDATE `bilgarasje` SET `curcity` = '{$tilby}',`transfer` = '{$tras}' WHERE `uid` = '{$obj->id}' AND `id` = '$bil'")){
                      $db->query("SELECT * FROM `oppuid` WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `oid` = '1' ORDER BY `oid` DESC LIMIT 1");
                      if($db->num_rows() == 1){
                        /*Sjekker om oppdrag 1 er aktivt*/
                        if($tilby == 1 && $obj->city == 2 && $f->bilid == 2 && $f->gotcity == 2 && $f->curcity == 2){
                          $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `tms` < '25' AND `oid` = '1' LIMIT 1");
                        }
                      }
                      $success['num']+=1;
                    }
                  }
                  else{
                    $errors['res'].=', fordi du ikke er i samme byen som bilen er i';
                    $errors['num']+=1;
                  }
                }
              }
            }
            else{
              $errors['res'].=', fordi den ikke er tilgjengelig';
              $errors['num']+=1;
            }
          }
          else{
            $errors['res'].=', fordi det er feil med by';
            $errors['num']+=1;
          }
        }/*Foreach END*/
        
        $errors['res'].='</p>';
        if($success['num'] >= 1){/*Kommer med successtekst og resultat*/
          $ant = ($success >= 2)?" bilene":" bilen";
          echo '<p class="lykket">Du sendte '.$success['num'].$ant.' til '.city($tilby).'</p>';
        }
        if($errors['num'] >= 1){
          $ant = ($success >= 2)?" biler":" bil";
          echo '<p class="feil">Det oppstod '.$errors["num"].' feil da du prøvde å sendte '.$ant.$errors["res"].'!';
        }
      }
      if(isset($_POST['selgalle'])){
        $now = time();
        $gc = $db->query("SELECT * FROM `bilgarasje` WHERE `transfer` < '$now' AND `transfer` <> '0' AND `uid` = '{$obj->id}' AND `curcity` <> `gotcity` AND `curcity` = '{$obj->city}' AND `sold` = '0'");
        if($db->num_rows() >= 1){
          $sum=0;
          $ant=$db->num_rows();
          $pre = ($ant == 1) ? null : 'er';
          while($b = mysqli_fetch_object($gc)){
              $sum = $sum + ($prizes[$b->bilid]);
              $db->query("UPDATE `bilgarasje` SET `sold` = '1' WHERE `uid` = '{$obj->id}' AND `id` = '{$b->id}' LIMIT 1")or die(mysql_error());
          }
          $db->query("UPDATE `users` SET `hand` = (`hand` + {$sum}) WHERE `id` = '{$obj->id}' LIMIT 1");
          echo '<p class="lykket">Du har solgt '.$ant.' bil'.$pre.' for '.number_format($sum).'kr!</p>';
        }//Biler finnes i byen
        else{
          echo '<p class="feil">Ingen biler er klare til å selges i '.city($obj->city).'!</p>';
        }
      }//Selg alle klare biler END
    }//Hvis satt END
    /*Garasje for biler, lister dem opp og gir valg til spilleren om:
    *Frakt
    *Salg
    *Salg av alle klare biler
    *Legge ut på auksjon(denne vil ta tid og kommer senere)
    */
    $sql = "SELECT * FROM `bilgarasje` WHERE `lostfound` = '0' AND `uid` = '{$obj->id}' AND `sold` = '0' ORDER BY `id` DESC";
    if($db->num_rows($db->query($sql)) >= 1){
        echo '
        <form method="post" action="" id="bilfrakt">
        <table class="table">
        <tr>
        <th><input type="checkbox" id="velgalle" onchange="derp();">Velg</th><th>Bil</th><th>Stjålet i</th><th>Nåværende by</th><th>I bevegelse?</th>
        </tr>';
        include("inc/bilconfig.php");
        $pagination = new Pagination($db,$sql, 20,'p');
        $pagination_links = $pagination->GetPageLinks();
        $garsje = $pagination->GetSQLRows();
        foreach($garsje as $garasjen){
            if(!isset($high)){
                $high = 0;
            }
            else{
                $high = $high + 1;
            }
            $biltime = $garasjen['transfer'] - time();
            echo '
            <tr class="biler">
            <td><input type="checkbox" name="checkbil[]" value="'.$garasjen['id'].'"></td>
            <td>'.$carz[$garasjen['bilid']].'</td>
            <td>'.city($garasjen['gotcity']).'</td>
            <td>'.city($garasjen['curcity']).'</td>
            <td>
            <span id="bil'.$garasjen['id'].'"></span>
            <script type="text/javascript">teller('.$biltime.',"bil'.$garasjen['id'].'",false,"ned");</script></td>
            </tr>
            ';
        }
        echo '<tr><td colspan="5">'.$pagination_links.'</tr></td>';
        echo '
        </table>
        <h1>Valg:</h1>
        <p><input style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" type="submit" class="button2" name="sendbil" value="Send til valgt by!" />
        <select style="-webkit-appearance: button;
-webkit-padding-end: 20px;border: 1px solid #AAA;color: #555;font-size: inherit;width: 107px;height: 20px;background-color: #aaa;" name="tilby">
        <option value="1">Til '.city(1).'</option>
        <option value="2">Til '.city(2).'</option>
        <option value="3">Til '.city(3).'</option>
        <option value="4">Til '.city(4).'</option>
        <option value="5">Til '.city(5).'</option>
        <option value="6">Til '.city(6).'</option>
        <option value="7">Til '.city(7).'</option>
        <option value="8">Til '.city(8).'</option>
        </select><br />
        <input class="button2" style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" type="submit" name="selgbil" value="Selg bilen!" /><br />
<input type="submit" style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" class="button2" name="selgalle" value="Selg biler som er klare!" /></p>
        </form>
        ';
        

                }
    else{
        echo '<p><em>Biler du har stjelt vil du finne her.</em></p>';
    }

    if(isset($_GET['verdier'])){
                    $now = time();
                $gc = $db->query("SELECT * FROM `bilgarasje` WHERE `uid` = '{$obj->id}' AND `sold` = '0'");
                if($db->num_rows() >= 1){
                    $sum=0;
                    $ant=$db->num_rows();
                    $pre = ($ant == 1) ? null : 'er';
                    while($b = mysqli_fetch_object($gc)){
                        $sum = $sum + ($prizes[$b->bilid]);
                    }
                    echo '<p>Verdien for alle bilene dine totalt er: '.number_format($sum).'kr!</p>';
                    echo '<p>Du har totalt '.$db->num_rows($gc).' bil'.$pre.' i din garasje!';
                }//Biler finnes i byen
                else{
                    echo '<p class="feil">Du har ingen biler!</p>';
                }
    }
    echo '</br><a class href="garasje.php?verdier">Sjekk verdiene på bilene!</a>';    
	}/*Fengsel/Bunker END*/
  ?>
<script type="text/javascript">
  function derp(){
    if(document.getElementById('velgalle').checked == true){
      a = document.getElementsByName("checkbil[]");
      for (index = 0; index < a.length; ++index) {
        document.getElementsByName("checkbil[]")[index].checked=true;
      }
    }
    else{
      a = document.getElementsByName("checkbil[]");
      for (index = 0; index < a.length; ++index) {
        document.getElementsByName("checkbil[]")[index].checked=false;
      }
    }
  }
</script>
<?php
    endpage();