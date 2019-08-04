<?php
include("core.php");
startpage("Bunker");
if (fengsel() == true) {
    echo '<h1>Bunker</h1>
	<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
	<script>
	teller(' . fengsel(true) . ')
	</script>
	';
} else if (bunker() == true) {
    $bu = bunker(true);
    echo '<h1>Bunker</h1>
	<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
	<script>
	teller(' . ($bu - time()) . ')
	</script>
	';
} else {
    $eiendommer = array(
      0=>array('navn' => "Fri", 'places' => 0, 'id' => 0, 'price' => 0),
      1=>array('navn' => "Pappeske", 'places' => 0, 'id' => 1, 'price' => 1000000),
      2=>array('navn' => "Telt", 'places' => 0, 'id' => 2, 'price' => 5000000),
      3=>array('navn' => "Campingvogn", 'places' => 2,'id' => 3, 'price' => 10000000),
      4=>array('navn' => "Liten leilighet med 1 soverom", 'places' => 4,'id' => 4, 'price' => 15000000),
      5=>array('navn' => "Leilighet med 3 soverom", 'places' => 6,'id' => 5, 'price' => 30000000),
      6=>array('navn' => "Hus med 3 soverom og kjeller", 'places' => 8, 'id' => 6, 'price' => 45000000),
      7=>array('navn' => "Palass", 'places' => 10, 'id' => 7, 'price' => 60000000),
      8=>array('navn' => "Slott", 'places' => 12, 'id' => 8, 'price' => 100000000)
    );
    $times = array("1" => 1800, "2" => 3600, "3" => 7200, "4" => 10800, "5" => 14400, "6" => 18000, "7" => 21600, "8" => 25200, "9" => 28800, "10" => 32400, "11" => 36000, "12" => 39600, "13" => 43200);
    $dont_touch_my_tralala = array(1800 => "30 minutter", 3600 => "1 time", 7200 => "2 timer", 10800 => "3 timer", 14400 => "4 timer", 18000 => "5 timer", 21600 => "6 timer", 25200 => "7 timer", 28800 => "8 timer", 32400 => "9 timer", 36000 => "10 timer", 39600 => "11 timer", 43200 => "12 timer");
    echo '<h1>Bunker</h1><p>Her kan du g&aring; i bunker, eller invitere andre til bunkeren din. Du kan kj&oslash;pe eiendom med bunker p&aring; <a href="/Marked">Svarteb&oslash;rsen</a>!</p>';
    $s = $db->query("SELECT * FROM `bunkerinv` WHERE `tid` = '{$obj->id}' AND `accepted` = '0' AND `used` = '0' AND `declined` = '0' AND `gone` = '0'");
    $antinv = $db->num_rows();
    if ($obj->eigendom == 0) {
        /*Spilleren har ikke kj&oslash;pt eiendom enda*/
    } else {
        $eiendom = $obj->eigendom;
        $title = $eiendommer[$eiendom]['navn'];
        $space = $eiendommer[$eiendom]['places'];

        $s2 = $db->query("SELECT * FROM `bunkerinv` WHERE `uid` = '{$obj->id}' AND `accepted` = '0' AND `used` = '0' AND `declined` = '0' AND `gone` = '0'");
        $antgiv = $db->num_rows();
        $left = $space - $antgiv;
        if (isset($_POST['uinv']) && isset($_POST['ubtid']) && isset($_POST['sendbunker'])) {
            /*Spilleren pr&oslash;ver &aring; sende bunker til andre spillere, pr&oslash;ver &aring; utf&oslash;re kommando*/
            $invs = $_POST['uinv'];
            $time = $_POST['ubtid'];
            //echo 'Samler informasjon!<br>';/*Skriveribugfiksetingtang*/
            for ($i = 0; $i <= count($invs); $i++) {
                if ($invs[$i] != "") {
                    $inv_inf[$i] = array("i" => $invs[$i], "t" => $times[$time[$i]]);
                }
            }
            //echo 'Behandler informasjon, del 1, antall arbeid: '.  count($inv_inf).'<br>';/*Skriveribugfiksetingtang*/
            for ($a = 0, $b = 0; $a < count($inv_inf); $a++) {
                if ((user_exists($inv_inf[$a]["i"]))) {
                    $names[$b] = array("i" => $inv_inf[$a]["i"], "t" => $inv_inf[$a]["t"]);
                    $b++;
                } else {
                    echo feil('' . htmlentities($inv_inf[$a]["i"]) . ' eksisterer ikke!');
                }
            }
            //echo 'Utf&oslash;rer siste beregninger<br>';/*Skriveribugfiksetingtang*/
            for ($o = 0; $o < count($names); $o++) {
                $t = $db->query("SELECT * FROM `bunkerinv` WHERE `tid` = '" . user_exists($names[$o]["i"], 1) . "' AND `uid` = '{$obj->id}' AND `used` = '0' AND `accepted` = '0' AND `declined` = '0' AND `gone` = '0'");
                if ($db->num_rows() >= 1) {/*Spiller har allerede f&aring;tt bunker*/
                    echo feil('Kan ikke sende bunker til ' . user(user_exists($names[$o]["i"], 1)) . ' da du allerede har invitert spilleren!');
                } else {
                    if (user_exists($names[$o]["i"], 1) == $obj->id) { /*Kan ikke sende til seg selv*/
                        echo feil('Du kan ikke sende bunker til deg selv!');
                    } else {
                        if ($db->query("INSERT INTO `bunkerinv`(`uid`,`tid`,`time`,`timeleft`,`length`) "
                            . "VALUES('" . $obj->id . "','" . user_exists($names[$o]["i"], 1) . "',UNIX_TIMESTAMP(),NULL,'" . $names[$o]['t'] . "')")) {
                            echo lykket('' . htmlentities($names[$o]["i"]) . ' mottok bunkerinvitasjon p&aring; ' . $dont_touch_my_tralala[$names[$o]['t']] . '!');
                        } else {
                            echo 'Det oppstod feil med query: ' . $db->last_query . "<br>" . mysqli_error($db->con) . "<br>";
                        }
                    }
                }
            }
        } else if (isset($_POST['deleteinv'])) {
            $id = $db->escape($_POST['slettid']);
            if (is_numeric($id) && $id >= 1) {
                $f = $db->query("SELECT * FROM `bunkerinv` WHERE `id` = '$id' AND `uid` = '{$obj->id}' AND `used` = '0' AND `declined` = '0' AND `gone` = '0'");
                $r = $db->fetch_object();
                if ($db->num_rows() == 1) {
                    if ($db->query("UPDATE `bunkerinv` SET `gone` = '1' WHERE `id` = '$id' LIMIT 1")) {
                        echo lykket('Invitasjonen til ' . status($r->tid, 1) . ' ble slettet!');
                    } else {
                        echo feil("Kunne ikke slette invitasjon!" . mysqli_error($db->con));
                    }
                } else {
                    echo feil("Invitasjonen du har valgt eksisterer ikke lengre, eller har blitt tatt i bruk!");
                }
            } else {
                echo feil('Feil valg, pr&oslash;v igjen!');
            }
        }
    }
  /*Viser tabeller osv*/
  /*F&oslash;rst vises tabell for invitasjoner fra andre*/
    if (isset($_POST['invacc'])) {
        $id = $db->escape($_POST['invacc']);
        if (isset($_POST['godtainv'])) {
            $db->query("SELECT * FROM `bunkerinv` WHERE `id` = '$id' AND `tid` = '{$obj->id}' AND `used` = '0' AND `declined` = '0' AND `gone` = '0'");
            if ($db->num_rows() == 1) {
                $o1 = $db->fetch_object();
                if ($db->query("UPDATE `bunkerinv` SET `accepted`='1',`timeleft`='" . (time() + $o1->length) . "',`used`='1' WHERE `id` = '$id'")) {
                    echo lykket('Du har n&aring; satt deg i bunker i ' . $times[$o1->length] . '!<br>Du er ute om: <span id="tidforute"></span><script>teller(' . (time() + $o1->length) . ')</script>');
                } else {
                    echo feil('Kunne ikke sette deg i bunker, queryfeil: ' . mysqli_error($db->con) . '<br>Query: "' . $db->last_query . '"');
                }
            } else {
                echo feil('Invitasjon finnes ikke.');
            }
        } else if (isset($_POST['slettinv'])) {
            $db->query("SELECT * FROM `bunkerinv` WHERE `id` = '$id' AND `tid` = '{$obj->id}' AND `used` = '0' AND `declined` = '0' AND `gone` = '0'");
            if ($db->num_rows() == 1) {
                $o1 = $db->fetch_object();
                if ($db->query("UPDATE `bunkerinv` SET `declined`='1' WHERE `id` = '$id' AND `tid` = '" . $obj->id . "'")) {
                    echo lykket('Du slettet invitasjonen!');
                } else {
                    echo feil('Kunne ikke slette invitasjon: ' . mysqli_error($db->con) . '<br>Query: "' . $db->last_query . '"');
                }
            } else {
                echo feil('Invitasjon finnes ikke.');
            }
        }
    }
    ?>
<form method="POST" action="">
  <table class="table">
    <thead>
      <tr>
        <th colspan="4">Invitasjoner</th>
      </tr>
      <tr>
        <th>Lengde</th><th>Fra</th><th>Mottat</th><th style="width:30px;">Velg</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($antinv >= 1) {
          while ($r = mysqli_fetch_object($s)) {
              echo '<tr><td>' . timec($r->length) . '</td><td>' . user($r->uid) . '</td><td>' . date("H:i:s d.m.y", $r->time) . '</td><td><input type="radio" name="invacc" value="' . $r->id . '"></td></tr>';
          }
      } else {
          echo '<tr>
        <td colspan="4" style="text-align:center;">Du har ikke mottat noen invitasjon.</td>
      </tr>';
        }
      ?>
      <tr>
        <td colspan="4" style="text-align: right"><input type="submit" class="button" name="godtainv" value="G&aring; i bunker!"><input type="submit" name="slettinv" value="Slett invitasjon"></td>
      </tr>
    </tbody>
  </table>
</form>
<form method="POST" action="">
  <table class="table">
    <thead>
      <tr>
        <th>Bunker</th>
      </tr>
      <tr>
        <th>Her kan du sende bunkerinvitasjoner til andre spillere om du har plass p&aring; eiendommen.</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($left == 0) {
          /*Ingen flere bunkerplasser tilgjengelig*/
      } else {
          while ($r = mysqli_fetch_object($s2)) {
              echo '<tr><td>' . user($r->tid) . ':' . timec($r->length) . '<input type="radio" name="slettid" value="' . $r->id . '"></td></tr>';
          }
          $leif = $left;
          $i = 1;
          for ($leif >= 1; $leif--;) {
              if ($left <= 0) {
                  break;
              }
              echo '<tr><td><input class="gudleif" data="' . $i . '" type="text" name="uinv[]" placeholder="Brukernavn"><select name="ubtid[]"><option value="1">30 min</option><option value="2">1 time</option><option value="3">2 timer</option><option value="4">3 timer</option><option value="5">4 timer</option><option value="6">5 timer</option><option value="7">6 timer</option><option value="8">7 timer</option><option value="9">8 timer</option><option value="10">9 timer</option><option value="11">10 timer</option><option value="12">11 timer</option><option value="13">12 timer</option></select></td></tr>';
              $i++;
          }
          if ($left >= 1) {
              echo '<tr><td colspan="3"><input type="submit" name="sendbunker" value="Send bunker til spiller!"></td></tr>';
          }
        }
      ?>
      <tr>
        <td colspan="3"><input name="deleteinv" type="submit" class="button" value="Slett invitasjonen!"></td>
      </tr>
    </tbody>
  </table>
</form>
<script>
  $(document).ready(function(){
    $("input[class='gudleif']").on("keyup",function(data){
      var form = $(this).serialize();
      //alert(" Verdi i felt: " + $(this).val() + "\n Data: " + $(this).attr("data"));
      var val = $(this).val();
      var dar = $(this).attr("data");
      console.log("Formdata: "+JSON.stringify(form));
      $.ajax({
        url:"handlers/handler8.php",
        data:'user='+val+'&data='+dar,
        accepts:"json",
        dataType:"json",
        type:"POST"
      }).done(function(data){
        var res = eval(data);
        console.log(res);
       if(res.res===1){
         $(this).css("background-color","green");
         $(this).animate({'height':'toggle'});
       }
       else{
         $(this).css("background-color","red");
         $(this).animate({'height':'toggle'});
       }
      });
    });
  });
</script>
    <?php
}
endpage();