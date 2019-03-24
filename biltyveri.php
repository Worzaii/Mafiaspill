<?php
include("core.php"); 
startpage("Biltyveri");
echo '<img src="imgs/biltyveri.png"><p>N&aring;r du f&oslash;rst starter med biltyveri, s&aring; vil du kun ha et valg. Ettersom du kommer opp i rank, s&aring; vil nye valg l&aring;ses opp.</p>';
if(fengsel() == true){
header("Location: /fengsel.php");
die();
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
  $time = time();
  $q = $db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `time` > '$time' ORDER BY `id` DESC LIMIT 0,1");
  if($db->num_rows() == 1){
    $qf = $db->fetch_object($q);
    $left = $qf->time - time();
    echo '<p class="feil">Du m&aring vente <span id="tid"></span> f&oslash;r neste gang!</p>
    <script>teller('.$left.',"tid",true,"ned");</script>';
  }
  else{
    if(isset($_POST['valget'])){
      $v = $db->escape($_POST['valget']);
      if(is_numeric($v) && $v >= 1){
        $s = $db->query("SELECT * FROM `biler` WHERE `id` = '$v' LIMIT 1");
        if($db->num_rows() == 1){
          $se = $db->fetch_object();
          if($se->bilmin > $se->bilmax || $se->bilmin < 1){
            echo '<p class="feil">Feil i bilvalg! Kontakt admin for &aring fikse!</p>';
          }
          else{
            $sa = $db->query("SELECT * FROM `chance` WHERE `type` = '2' AND `uid` = '$obj->id' AND `option` = '$v'");
            if($db->num_rows() == 1){
              $si = $db->fetch_object();
              $time = time() + $se->aftertime;
              if(rand(1,100) > $si->percent){
                //Om random tall er h&oslash;yere en sjanse, s&aring; fail
                if(rand(1,2) == 1){
                  echo '<p class="feil">Du klarte det ikke!</p>';
                  if(!$db->query("INSERT INTO `billogg`(`uid`,`time`,`timelast`,`resu`,`bvalg`) VALUES('$obj->id','$time','".time()."','0','$v')")){
                    if($obj->status == 1){
                      echo mysqli_error($db->connection_id);
                    }
                    else{
                      echo '<p>Det var feil med en sp&oslash;rring, og det ble lagret i loggen. Varsle Ledelsen om dette snarest, slik at de kan se igjennom det.</p>';
                    }
                  }
                }
                else{
                  echo '<p class="feil">Du klarte det ikke og havnet i fengselet! G&aring;r til fengsel om 3 sekunder</p>';
                  $time = time();
                  $time2 = time() + 90;
                  if(!$db->query("INSERT INTO `billogg`(`uid`,`time`,`timelast`,`resu`,`bvalg`) VALUES('$obj->id','$time2','".time()."','2','$v')")){
                    if($obj->status == 1){
                      echo mysqli_error($db->connection_id);
				 header('Refresh: 3;url=fengsel.php');
                  die();
                    }
                  }
                  if($db->query("INSERT INTO `jail`(`uid`,`reason`,`time`,`timeleft`) VALUES('$obj->id','Pr&oslash;vde &aring stjele bil','$time','$time2')")){
                    echo '<p class="feil">Tid igjen: <span id="fengsel"></span><script>teller(90,"fengsel",true,"ned");</script></p>';
                  }
                }
              }
              else{
                include_once("inc/bilconfig.php");
                if(count($idz) != count($carz) || count($idz) != count($prizes) || count($prizes) != count($carz)){
                  echo '<p class="feil">Det er en feil i bilconfig! Vennligst rapporter dette til Admin snarest!</p>';
                }
                else{
                  $whatcar =(rand($se->bilmin,$se->bilmax) - 1);//Velger biler
                  echo '<p class="lykket">Du fikk med deg '.$carz[$whatcar].' med en verdi p&aring '.number_format($prizes[$whatcar]).'!</p>';
                  if($db->query("INSERT INTO `bilgarasje`(`bilid`,`uid`,`gotcity`,`curcity`,`time`) VALUES('{$whatcar}','{$obj->id}','{$obj->city}','{$obj->city}','$time')")){
                  if($db->query("INSERT INTO `billogg`(`uid`,`time`,`timelast`,`resu`,`bvalg`) VALUES('$obj->id','$time',UNIX_TIMESTAMP(),'1','$v')")){
                  if($db->query("UPDATE `users` SET `exp` = (`exp` + {$se->exp}) WHERE `id` = '{$obj->id}' LIMIT 1")){
                  /*Ingen kommentar*/
                  }
                  }
                  else{
                  echo '<p class="feil">'.mysqli_error($db->connection_id).'</p>';
                  }
                  }
                }
              }
              if($si->percent >= 74){
                $ran2 = rand(10,36);
                $db->query("UPDATE `chance` SET `percent` = (`percent` - $ran2) WHERE `uid` = '".$obj->id."' AND `option` = '".$si->option."' LIMIT 1");
                $si->percent-=$ran2;
              }
              else{
                $ran2 = rand(1,3);
                $db->query("UPDATE `chance` SET `percent` = (`percent` + $ran2) WHERE `uid` = '$obj->id' AND `option` = '$si->option' LIMIT 1");
                $si->percent+=$ran2;
              }
            }
            else{
              feil('
              <p>Beklager, det kan se ut som det er feil med din sjanse-sjekker. Ta kontakt med admin for &aring; f&aring; dette fikset.</p>
              ');
            }
          }
        }
        else{
        feil('<p class="feil">Dette valget eksisterer ikke!</p>');
        }
      }
      else{
        feil('<p class="feil">Det er ikke gyldig valg, da verdien ikke er tall! #Nohacksplease!</p>');
      }
    }
    ?>
    <div id="biltyveri">
      <form method="post" id="bil" action="">
        <table class="table" style="width:590px;">
          <tr>
            <th colspan="3">Stjel biler</th>
          </tr>
          <tr class="c_3">
            <td>Oppgave</td>
            <td>Sjanse</td>
            <td>Ventetid</td>
          </tr>
          <?php
          $rank = rank($obj->exp);
          $s = $db->query("SELECT * FROM `biler` WHERE `level` < '".$rank[0]."' ORDER BY `level` DESC,`id` DESC");
          if($db->num_rows() >= 1){
            while($r = mysqli_fetch_object($s)){
            $sql2 = $db->query("SELECT * FROM `chance` WHERE `uid` = '{$obj->id}' AND `type` = '2' AND `option` = '{$r->id}'");
            if($db->num_rows() >= 1){
              $get2 = $db->fetch_object($sql2);
              $sjanse = $get2->percent.'%';
            }
            else {
              $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES('$obj->id','2','$r->id')");
              $sjanse = "0%";
            }
            echo '
            <tr class="valg notactive" onclick="sendpost('.$r->id.')">
            <td>'.htmlentities($r->handling, ENT_NOQUOTES | ENT_HTML401, "ISO-8859-1").'</td><td>'.$sjanse.'</td><td>'.timec($r->aftertime).'</td>
            </tr>
            ';
            }
          }
          else{
          echo '<tr><td colspan="3"><em>Ingen oppgaver er tilgjengelige!</em></td></tr>';
          }
          ?>
        </table>
        <input type="hidden" value="" name="valget" id="valget">
      </form>
      <script language="javascript">
      function sendpost(valg) {
      $('#valget').val(valg);
      $('#bil').submit();
      } 

      $(document).ready(function(){
      $('.valg').hover(function(){
      $(this).removeClass().addClass('c_2').css('cursor','pointer');
      },function() {
      $(this).removeClass().addClass('c_1').css('cursor','pointer');
      });
      });   
      </script>
    </div>
    <?php
  }/*I fengsel END*/
}/*Ventetid END*/
endpage();
?>