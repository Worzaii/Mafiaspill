<?php
  include("core.php");
  //startpage("Auksjon");
    if(fengsel() == true){
echo '
<p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller('.fengsel(true).',\'krim\',true,\'ned\');
</script>
';
}
else if(bunker() == true){
$bu = bunker(true);
echo '
<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br />Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
<script type="text/javascript">
teller('.($bu - time()).',\'bunker\',false,\'ned\');
</script>
';
}
else{
  $res=NULL;
  /*Sjekker om det er noen runder som er ferdige før noen handler utføres*/
  $q = $db->query("SELECT * FROM `auksjon` WHERE `time_left` < UNIX_TIMESTAMP() AND `done` = '0' OR `done` = '0' AND `autowin` = `currentbid` ORDER BY `id` ASC");
  if($db->num_rows() >= 1){
    /*Det er noen rader som må gjøres noe med, fullfører handler*/
    while($r = mysqli_fetch_object($q)){
      if($r->autowin == $r->currentbid){
        /*Hvis noen har gitt siste bud så vil de få auksjonen med en gang*/
        $db->query("SELECT * FROM `budauk` WHERE `aid` = '$r->id' ORDER BY `id` DESC LIMIT 1");/*Henter siste bud*/
        $usr = $db->fetch_object();/*Siste bud*/
        /*Noen har bydd summen spilleren ønsket, utfører handel om ikke fullført*/
        $db->query("UPDATE `auksjon` SET `done` = '1' WHERE `id` = '$r->id'")or die(mysqli_error($db->connection_id));
        $db->query("UPDATE `users` SET `bank` = (`bank` + ".$r->currentbid.") WHERE `id` = '$r->seller'")or die(mysqli_error($db->connection_id));
        $db->query("UPDATE `firma` SET `Eier` = '{$usr->uid}' WHERE `id` = '$r->item'")or die(mysqli_error($db->connection_id));
        $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES('".$usr->uid."','".time()."','".$db->slash('--<b>Auksjon!</b><br />Du har mottat et firma, gå hit for å se dine firmaer: <a href="Firmaer">Firmasenter</a>! Du kjøpte firmaet ifra '.user($r->seller).' til '.number_format($r->currentbid).' kr!')."'),('".$r->seller."','".time()."','".$db->slash('--<b>Auksjon!</b><br />Ditt firma har blitt solgt til '.user($usr->uid).' for '.number_format($r->currentbid).' kr!')."')");
        //echo '<br />4. Berørte rader: '.$db->affected_rows();
        }
        else if($r->time_left < time()){
          /*Tiden er ute, sjekker om det er noen bud, hvis ikke blir det stemplet som ferdig og ingen overtar firmaet*/
          if($r->currentbid == 0){
            /*Ingen har gitt bud for salget*/
            $db->query("UPDATE `auksjon` SET `done` = '1' WHERE `id` = '$r->id' LIMIT 1");
          }
          else{
            /*Ser etter bud*/
            $db->query("SELECT * FROM `budauk` WHERE `aid` = '$r->id' ORDER BY `id` DESC LIMIT 1");/*Siste budet*/
            if($db->num_rows() == 1){
              $res = $db->fetch_object();
              $db->query("UPDATE `users` SET `bank` = (`bank` + ".$res->bid.") WHERE `id` = '$r->seller'");
              $db->query("UPDATE `firma` SET `Eier` = '$res->uid' WHERE `id` = '$r->item'");
              sysmel($r->seller,'--<b>Auksjon</b><br />Ditt firma har blitt solgt til '.user($res->uid).' til '.number_format($res->bid).' kr da ingen bydde ditt krav på '.number_format($r->autowin).'!');
              sysmel($res->uid,'--<b>Auksjon!</b><br />Du har mottat et firma, gå hit for å se dine firmaer: <a href="Firmaer">Firmasenter</a>! Du kjøpte firmaet ifra '.user($r->seller).' til '.number_format($res->bid).' kr!');
              $db->query("UPDATE `auksjon` SET `done` = '1' WHERE `id` = '$r->id'");
            } 
          }
        }
    }
  }
  if(isset($_GET['ny'])){
    if(isset($_POST['handlemate'])){
      /*Selger/legger ut til auksjon*/
      $id = $db->escape($_POST['choose']);/*ID til salg*/
      $h = $db->escape($_POST['handlemate']);/*Salgsmåte*/
      $fi = firma($id);
      if($fi != false){
        if($obj->id != $fi[1]){
          /*Brukeren eier ikke firmaet, derfor kan han ikke legge ut*/
          $res = '<p class="feil">Du kan ikke selge dette firmaet, da du ikke eier det selv!</p>';
        }
        else{
          $a = $db->query("SELECT * FROM `auksjon` WHERE `item` = '$id' AND `done` = '0' ORDER BY `id` DESC LIMIT 1");
          if($db->num_rows() == 1){
            $res = '<p class="feil">Dette firmaet er allerede til salgs/auksjonering!</p>';
          }
          else{
            /*Fortsetter validering*/
            if($h == 0){
              /*Legge ut firmaet til èn pris*/
              $res = '<p>Salg er ikke klart enda!</p>';
            }
            else if($h==1){
              /*Spilleren vil auksjonere bort firmaet*/
              $listid=array(1=>3600,2=>7200,3=>10800,4=>18000,5=>43200,6=>86400,7=>172800,8=>259200,9=>345600,10=>432000,11=>518400,12=>604800,13=>1209600,14=>1814400,15=>2419200);/*Alle tidsverdiene som oppsett for auksjonstid.*/
              $min = $db->escape($_POST['minbud']);
              $aw = $db->escape($_POST['aw']);
              $incr = $db->escape($_POST['incr']);
              $tid = $db->escape($_POST['period']);
              /*Spilleren vil selge firmaet for en fast pris*/
              if($db->query("INSERT INTO `auksjon`(`item`,`lowbid`,`autowin`,`increasebid`,`start`,`time_left`,`seller`) VALUES('".$id."','".$min."','".$aw."','".$incr."','".time()."','".(time() + $listid[$tid])."','".$obj->id."')")){
                $res = '<p class="lykket">Du har lagt ut firmaet ditt til auksjonering. Om det ikke kommer inn bud før fristen går ut vil det ikke lengre auksjoneres bort, og du vil fortsette å eie firmaet.</p>';
              }
              else{
                $res='<p class="feil">Kan ikke legge ut firmaet, be ledelsen sjekke feilloggen for "auksjon.php".</p>';
              }
            }
          }
        }
      }
      else{
        $res = '<p class="feil">Beklageligvis kan vet virke som at dette firmaet har en feil et eller annet sted, ta kontakt med ledelsen for å rette i problemet!<br /></p>';
      }
    }
    /*Legge ut noe nytt til salgs*/
    $owning = NULL;
    $s = $db->query("SELECT * FROM `firma` WHERE `Eier` = '".$obj->id."' ORDER BY `id` ASC")or die(mysqli_error($db->connection_id));
    if($db->num_rows() >= 1){
      /*Spilleren eier firmaer, legger de inn i listen*/
      $listorder = 0;
      while($r = mysqli_fetch_object($s)){
        if($r->Type == 1 && $r->By == 0){
          /*Spesielt unntak for Lottofirmaet*/
          $owning .= '<tr class="selge" onclick="velg_verdi('.$r->id.','.$listorder.');"><td><span title="'.$r->Navn.'">Lottofirma</span></td></tr>';
          $listorder++;
        }
        else{
          if($r->Type==2)$type="Flyplass";
          $owning .='<tr class="selge" onclick="velg_verdi('.$r->id.','.$listorder.');"><td><span title="'.$r->Navn.'">'.$type.' i '.city($r->By).'</span></td></tr>';
          $listorder++;
        }
      }
    }
    else{
      $owning = '<tr><p>Du eier ingenting som du kan selge!</p></tr>';
    }
    startpage("Auksjonshuset - Nytt salg");
    ?>
<h1>Auksjonshuset - Nytt salg</h1>
<img src="imgs/auksjon.png">
<p><a href="/Auksjon">Tilbake til Auksjonshuset!</a></p>
<?=$res?>
<form method="post" action="auksjon.php?ny">
  <table class="table">
    <thead>
      <tr>
        <th>Liste over det du kan selge fra deg</th>
      </tr>
    </thead>
    <tbody>
      <?=$owning?>
    </tbody>
  </table>
  
  <p>Velg handlemåte:<br>
    Salg:<input type="radio" class="choice" checked="" name="handlemate" value="0"><br>
    Auksjon:<input type="radio" class="choice" name="handlemate" value="1">
  </p>
  <div id="distab2">
    <table class="table">
      <thead>
        <tr>
          <th colspan="2">Salgsvalg</th>
        </tr>
        <tr>
          <td colspan="2">Hva vil du ha for firmaet?</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Penger:</td><td><input type="number" min="0" value="0" name="monypay"></td>
        </tr>
        <tr>
          <td>Poeng:</td><td><input type="number" min="0" value="0" name="ponypay"></td>
        </tr>
        <tr>
          <td>Kuler:</td><td><input type="number" min="0" value="0" name="bulepay"></td>
        </tr>
        <tr>
          <th style="text-align:center;" colspan="2"><input class="submit" value="Legg ut salget!"></th>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="distab" style="display:none;">
  <table class="table">
    <thead>
      <tr>
        <th colspan="2">Auksjonsvalg</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Minstebud/Startbud:</th><td><input type="number" min="100" value="100" name="minbud"></td>
      </tr>
      <tr>
        <th>Autowin:</th><td><input type="number" min="100" value="100" name="aw"></td>
      </tr>
      <tr>
        <th>Budøkning:</th><td><input type="number" min="100" value="100" name="incr"></td>
      </tr>
      <tr>
        <th>Varighet:</th><td>
          <select name="period">
            <optgroup label="Timer">
              <option value="1">1 time</option>
              <option value="2">2 timer</option>
              <option value="3">3 timer</option>
              <option value="4">5 timer</option>
              <option value="5">12 timer</option>
            </optgroup>
            <optgroup label="Dager/Uker">
              <option value="6">1 dag</option>
              <option value="7">2 dager</option>
              <option value="8">3 dager</option>
              <option value="9">4 dager</option>
              <option value="10">5 dager</option>
              <option value="11">6 dager</option>
              <option value="12">1 uke</option>
              <option value="13">2 uker</option>
              <option value="14">3 uker</option>
              <option value="15">1 måned(max)</option>
            </optgroup>
          </select>
        </td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" value="" name="choose" id="salg">
  <input type="submit" value="Legg ut til handel!">
  </div>
</form>
<script type="text/javascript" language="javascript">
  $(document).ready(function () {
    $(".selge").css('cursor','pointer').hover(function () {
      $(this).find('td').not('.valgt').removeClass().addClass('normrad1');
    },function () {
      $(this).find('td').not('.valgt').removeClass();
    });
  });
  $(".choice").change(function(){
    $vaff = $(this).val();
    if($vaff == 1){
      /*Bytter til auksjon, animerer*/
      $("#distab2").fadeOut(250);
      $("#distab").fadeIn(250);
    }
    else if($vaff == 0){
      $("#distab").fadeOut(250);
      $("#distab2").fadeIn(250);
    }
  });
  function velg_verdi(value,rad) {
    merk_class("selge",rad);
    $("#salg").val(value);
  }
  function merk_class(classen,index) {
    iden = "." + classen;
    $(iden).find("td").removeClass();
    $(iden).eq(index).find("td").removeClass().addClass("normrad1 valgt");
  }
</script>
<?php
  }
  /*Feil mellom: A*/
  else{
    /*Ny auksjon/salg ikke tilgjengelig*/
  if(isset($_POST['budin']) && isset($_POST['budnummer']) && isset($_POST['auksjonsid'])){
    $id = $db->escape($_POST['auksjonsid']);
    $sum= $db->escape($_POST['budnummer']);
    if(!is_numeric($sum) || $sum <= 0){
      $re = '<p class="feil">Du må legge inn et gyldig bud, det aksepteres kun tall som er høyere enn 0!</p>';
    }
    else{
      $db->query("SELECT * FROM `auksjon` WHERE `id` = '$id' AND `done` = '0' AND `time_left` > '".time()."' AND `currentbid` < `autowin`");
      if($db->num_rows() == 1){
        /*Velger auksjon fra id om runden ikke er ferdig eller har autowin*/
        $resu=$db->fetch_object();
        if($resu->seller == $obj->id){
          /*Den som selger kan ikke kjøpe opp det som en selv la ut til salg*/
          $re = '<p class="feil">Du kan ikke legge inn bud på egen auksjon!</p>';
        }
        else{
          $db->query("SELECT * FROM `budauk` WHERE `aid` = '".$id."' ORDER BY `id` DESC LIMIT 1");
          if($db->num_rows() == 1){
            /*Finnes bud fra før*/
            $budbefore = TRUE;
            $bud = $db->fetch_object();
            if($obj->id == $bud->uid){
              /*Spiller prøver å legge inn bud enda han er den siste som har lagt inn bud*/
              $re = '<p class="feil">Du kan ikke legge inn bud, da du er den siste som la inn budet. Vent til noen andre har lagt inn bud før du prøver igjen.</p>';
              $everything_is_ok = FALSE;
            }
            else if($sum <= $bud->bid){
              /*Budet er likt forrige bud, aksepteres ikke*/
              $everything_is_ok = FALSE;
              $re = '<p class="feil">Du har bydd for lite penger i forhold til nåværende bud. By høyere. Neste gyldige bud er på '.(number_format(($bud->bid + $resu->increasebid))).' kr!</p>';
            }
            else if($sum > $bud->bid && ($bud->bid + $resu->increasebid) > $sum && $resu->autowin != $sum){
              /*Budet er høyere, men oppfyller ikke kravet om å øke budet en viss sum, men skulle spilleren by aw enda økningen ikke fyller kravet, vil budet bli godtatt likevell*/
              $everything_is_ok = FALSE;
              $re = '<p class="feil">Du har bydd en sum som ikke stemmer overens med budøkning. Du må by minst '.(number_format(($bud->bid + $resu->increasebid))).' kr!</p>';
            }
            else{
              /*Budet oppfyller kravet, og settes inn evt*/
              $everything_is_ok = TRUE;
              $budbefore = TRUE;
            }
          }
          else{
            /*Ingen har lagt inn bud, legger inn sjekk i $every*/
            $everything_is_ok = true;
            $budbefore = FALSE;
          }
          if($everything_is_ok == true){
            /*Fortsetter validering av bud*/
            if($sum >= $resu->lowbid){
              /*Spilleren byr godkjent bud*/
              if($obj->hand >= $sum){
                /*Legger inn budet*/
                $db->query("INSERT INTO `budauk` VALUES(NULL,'".$obj->id."','".$id."','".$sum."','".time()."')");
                $db->query("UPDATE `users` SET `hand` = (`hand` - $sum) WHERE `id` = '".$obj->id."' LIMIT 1");
                $db->query("UPDATE `auksjon` SET `currentbid` = '$sum' WHERE `id` = '$id'");
                /*Ettersom en bruker har bydd penger, så vil det forrige budet, om det er noen andre bud, bli satt tilbake til spillerens bank*/
                if($budbefore == true){
                  /*Gir tilbake spilleren de pengene som er bydd*/
                  $db->query("UPDATE `users` SET `bank` = (`bank` + ".$bud->bid.") WHERE `id` = '".$bud->uid."' LIMIT 1");
                }
                $re = '<p class="lykket">Du har lagt inn et bud på '.number_format($sum).'kr!';
              }   
              else{
                $re = '<p class="feil">Du kan ikke legge inn budet på runden, da du ikke har nok penger på handa. Du mangler '.(number_format($sum - $obj->hand)).'kr.</p>';
              }
            }
            else {
              /*Spilleren legger ikke inn gyldig bud*/
              $re = '<p class="feil">Du må legge inn et bud som oppfyller kravet for minstebudet. Minstebudet er på '.number_format($resu->lowbid).'kr, mens du ga et bud på '.number_format($sum).'kr.';
            }
          }
        }
      }
      else{
        /*Auksjon over/eksisterer ikke*/
        $re = '<p class="feil">Auksjonen er ikke gyldig eller eksisterer ikke lengre. Oppdater siden for å se etter aktive auksjoner.';
      }
    }
  }/*Issetbud end*/
  startpage("Auksjonshuset",'
<script type="text/javascript">
/*Script laget av Nicholas Arnesen
Den fungerer slik at om man ikke har javascript tilgjengelig, så vil ikke hjelp vises.*/
  $(document).ready(function(){
  $("#tog").hover(function(){
    $(this).css(\'cursor\',\'pointer\');
  });
  $("#tog").css(\'display\',\'block\');;
   $("#tog").click(function(){
    $("#helpinfo").toggle(600);
   }); 
  });
</script>    
');
  echo '<h1>Auksjonshus</h1><img src="imgs/auksjon.png" alt="Auksjon"><p><a href="/Auksjon?ny">Legg inn nytt salg/auksjon!</a></p>';
  $s1 = $db->query("SELECT * FROM `auksjon` WHERE `done` = '0' AND `time_left` > '".time()."' AND `currentbid` < `autowin` ORDER BY `id` DESC");
  if($db->num_rows() >= 1){
    $res = '';
    if(r1()||r2()){
      $show = true;
    }
    while($r = mysqli_fetch_object($s1)){
      /*Skriver alle auksjonene til variabel som skrives ut etterpå*/
      $firmaet = firma($r->item);
      if($firmaet[2]==2){
        /*Det er en flyplass*/
        $ting="Flyplassen i ".city($firmaet[4]);
      }
      else if($firmaet[2] == 1){
        /*Lottofirma*/
        $ting="Lottofirma";
      }
      if(!isset($high)){
        $high = 0;
      }
      else{
        $high = $high + 1;
      }
      $tidigjen = $r->time_left - time();
      $curbid = ($r->currentbid == 0) ? "Ingen bud" : number_format($r->currentbid);
      if($show){
        $q = $db->query("SELECT * FROM `budauk` WHERE `aid` = '$r->id' ORDER BY `id` DESC LIMIT 1");
        if($db->num_rows() == 1){
          $f = $db->fetch_object();
          $addd = '<td>'.user($f->uid).'</td>';
        }
        else{
          $addd = '<td>Ingen..</td>';
        }
      }
      $res .='<tr class="auksjonsliste bud" onclick="velg_auk('.$r->id.','.$high.')">
        <td>'.$ting.'</td>
        <td>'.number_format($r->lowbid).'kr</td>
        <td>'.$curbid.'</td>
        <td>'.number_format($r->autowin).'kr</td>
        <td>'.number_format($r->increasebid).'kr</td>
        <td><span id="teller'.$r->id.'"></span><script type="text/javascript">teller('.$tidigjen.',"teller'.$r->id.'",false,"ned");</script></td>
        <td>'.user($r->seller).'</td>
          '.$addd.'
        </tr>';
    }
  }
  else{
    $res = '<tr><th colspan="7"><em>Det er ingen aktive auksjoner i spillet!</em></th></tr>';
  }
  /*Feil mellom: B*/
  ?>
<p id="tog" style="display: none;">Klikk her for å se hvordan auksjon fungerer!</p>
<div style="display: none;" id="helpinfo">
  <p>Alle spillere har mulighet til å legge ut en auksjon/et salg.<br />Når noen byr, så må de enten by minstebud om ingen har bydd før, eller by mer enn forrige som har bydd + økning av budet som også står der.<br />
    Når Auksjonen er over, så vil det høyste budet stikke av med premien, og forrige eier vil ikke lengre forbli eier av verdien.<br />
  Om en spiller har bydd og står som siste byder i auksjonen, så kan han/hun ikke by igjen før noen andre har bydd over han/hun.</p></div>
<form method="post" action="">
  <table class="table">
    <thead>
      <tr>
        <th colspan="8">Auksjonshuset!</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Hva</th><th>Startbud</th><th>Gjeldende bud</th><th>Autowin</th><th>Minste økning i bud</th><th>Tid igjen</th><th>Selger</th><?php if($show){echo '<th>Byder</th>';}?>
      </tr>
      <?php
        /*Sjekker om det er noen aktive runder.
         * Dette scriptet er avhengig av 2 tabeller. En for visning her, og en for budene.
         * Disse tabellene gjelder: `budauk` & `auksjon`!
         */
      echo $res;
      ?>
    </tbody>
  </table>
  <input type="hidden" value="" name="auksjonsid" id="auksjon"><input type="number" name="budnummer" placeholder="Ditt Bud"><input type="submit" value="Legg inn bud!" name="budin">
</form>

<script type="text/javascript" language="javascript">
$(document).ready(function () {
  $(".auksjonsliste").css('cursor','pointer').hover(function () {
    $(this).find('td').not('.valgt').removeClass().addClass('normrad1');
  },function () {
    $(this).find('td').not('.valgt').removeClass();
  });
});
function velg_auk(value,elm) {
  merk_class("bud",elm);
  $("#auksjon").val(value);
}
function merk_class(classen,index) {
  var iden = "." + classen;
  $(iden).find("td").removeClass();
  $(iden).eq(index).find("td").removeClass().addClass("normrad1 valgt");
}
</script>
<?php 
  }
}
endpage();