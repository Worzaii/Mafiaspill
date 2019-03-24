<?php
  include("core.php");
  startpage("Kriminalitet");
  echo '<img src="imgs/krim.png"><p>N&aring;r du f&oslash;rst starter med kriminalitet, s&aring; vil du kun ha et valg. Ettersom du kommer opp i rank, s&aring; vil nye valg l&aring;ses opp.</p>';
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
    $q = $db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id' AND `time` > '".time()."' ORDER BY `id` DESC LIMIT 0,1");
    if(!$q){
      if($obj->status == 1){
        echo '
        <p class="feil">Tidssp&oslash;rring kunne ikke utf&oslash;res: '.mysqli_error($db->connection_id).'</p>
        ';
      }
      else{
        echo '
        <p class="feil">Det har oppst&aring;tt en feil, vennligst kontakt Admin via Support for &aring; f&aring; rettet p&aring; dette!</p>
        ';
      }
    }
    else if($db->num_rows() == 1){
      /*Sjekker tid*/
      $f = $db->fetch_object($q);
      if(time() < $f->time){
        $tid = $f->time - time();
        echo '
        <p class="feil">Du m&aring; vente <span id="krim">'.$tid.'</span> f&oslash;r neste krim.</p>
        <script>
        teller('.$tid.',\'krim\',true,\'ned\');
        </script>
        ';
      }
    }
    else if($db->num_rows() == 0){
      /*Krimen er klar til &aring; tas.*/
      if(isset($_POST['valget'])){
      if(empty($_POST['valget'])){
        echo '<p class="feil">Du m&aring; velge et alternativ f&oslash;rst!</p>';
      }
      else{
      $val = $db->escape($_POST['valget']);
      $valg = $db->query("SELECT * FROM `krim` WHERE `id` = '$val' LIMIT 0,1");
      if(!$valg){
      if($obj->status == 1){
      echo '
      <p>Feil i sp&oslash;rring1: '.mysqli_error($db->connection_id).'</p>
      ';
      }
      else{
        echo '
        <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til en Admin!</p>
        ';
      }
      }
      else{
        /*Fortsetter med krimutf&oslash;ringen*/
        $v = $db->fetch_object($valg);
        $sj = $db->query("SELECT * FROM `chance` WHERE `uid` = '".$obj->id."' AND `type` = '1' AND `option` = '".$val."'");
        if($db->num_rows() == 0){
          /*Om sjansen ikke eksisterer, s&aring; opprettes en ny*/
          $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES('$obj->id','1','$val')");
        }
        else{
          $vf = $db->fetch_object($sj);
          if($vf->percent >= 74){
            /*Sjansen er blitt s&aring;pass h&oslash;y at vi setter den ned.*/
            $ran2 = rand(10,46);
            $db->query("UPDATE `chance` SET `percent` = (`percent` - $ran2) WHERE `uid` = '".$obj->id."' AND `option` = '".$vf->option."' AND `percent` > '73' LIMIT 1");
            $vf->percent +=$ran2;//Oppdaterer objektet, slik at prosenten stemmer med det samme
          }
          else if($vf->percent <= 73){
            $ran2 = rand(1,3);
            $db->query("UPDATE `chance` SET `percent` = (`percent` + $ran2) WHERE `uid` = '$obj->id' AND `option` = '$vf->option' AND `percent` < '46'");
            $vf->percent+=$ran;
          }
        }
        $sjanse = $vf->percent;
        $kr = mt_rand($v->minm,$v->maxm);
        $time = $v->wait + time();
        $time2 = $time - time();
        if(mt_rand(0,100) <= $sjanse){
          /*/*Noen hadde satt random til mellom 1 og 3, dermed gjort det 100% mulig &aring; klare krimmen...*/
          if($db->query("UPDATE `users` SET `hand` = (`hand` + $kr),`exp` = (`exp` + $v->expgain) WHERE `user`= '$obj->user' LIMIT 1")){
            if($db->query("INSERT INTO `krimlogg`(`usid`,`time`,`valid`,`resu`,`timelast`) VALUES('$obj->id','$time','{$v->id}','1','".time()."')")){
              $db->query("SELECT * FROM `oppuid` WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `oid` = '2' ORDER BY `oid` DESC LIMIT 1");
              if($db->num_rows() == 1){
                /*Sjekker om oppdrag 1 er aktivt*/
                if($time - time()){
                  $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `tms` < '250' AND `oid` = '2' LIMIT 1");
                }       
              }
              $time = $time - time();
              echo '
              <p class="lykket">Du var heldig og fikk '.number_format($kr).'kr med deg!</p>
              <p class="feil">Tid til neste krim: <span id="krim">'.$time2.'</span>.</p>
              <script tyoe="text/javascript">
              teller('.$time2.',\'krim\',true,\'ned\');
              </script>
              ';
            }
            else{
              if($obj->status == 1){
                echo '
                <p>Feil i sp&oslash;rring2: '.mysqli_error($db->connection_id).'</p>
                ';
              }
              else{
                echo '
                <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support, slik at de kan se i loggen hva som hendte!</p>
                ';
              }
            }
          }
          else{
            if($obj->status == 1){
              echo '
              <p>Feil i sp&oslash;rring: '.mysqli_error($db->connection_id).'</p>
              ';
            }
            else{
              echo '
              <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support!</p>
              ';
            }
          }
        }
        else{
          if($db->query("INSERT INTO `krimlogg`(`usid`,`time`,`valid`,`resu`) VALUES('$obj->id','$time','$val','0')")){
            $fen = rand(0,1);
            if($fen == 1){
              echo '
              <p class="feil">Du klarte det ikke!</p>
              <p class="feil">Tid til neste krim: <span id="krim">'.$time2.'</span>.</p>
              <script>
              teller('.$time2.',\'krim\',true,\'ned\');
              </script>
              ';
            }
            else{
              $time = time();
              $time2 = time() + 120;
              $q = $db->query("INSERT INTO `jail`(`uid`,`reason`,`time`,`timeleft`,`prisut`) VALUES('$obj->id','Utf&oslash;rte kriminelle handlinger!','$time','$time2',2500000)");
              $timeleft2 = 120;
              echo '
              <p class="feil">Du klarte det ikke, og politiet oppdaget deg! G&aring;r til fengsel om 3 sekunder</p>';
              header('Refresh: 3;url=fengsel.php');
              die();
              if($db->affected_rows() == 1){
                echo '
                <p class="feil">Du ble satt i fengsel! <br>Gjenst&aring;ende tid: <span id="krim2">'.$timeleft2.'</span>.</p>
                <script>
                teller('.$timeleft2.',\'krim2\',true,\'ned\');
                </script>
                ';
              }
              else{
                echo '<p class="feil">Klarte ikke &aring; sette deg i fengsel! </p>';
              }
            }
          }
        }
      }
      }
      }
      ?>
      <form name="krim" method="post" id="krim" action="">
        <table style="width:590px;" class="table">
          <tr>
            <th colspan="4">Krimhandlinger</th>
          </tr>
          <tr>
            <td><b>Handling</b></td>
            <td style="width:80px;"><b>Fortjeneste</b></td>
            <td><b>Ventetid</b></td>
            <td><b>Sjanse</b></td>
          </tr>
          <?php
          $rank = rank($obj->exp);
          $ranknr=$rank[0];
          $sql = $db->query("SELECT * FROM `krim` WHERE `krav` <= '$ranknr' ORDER BY `krav` DESC,`id` DESC")or die('<p>Kunne ikke hente handlinger!</p>');
          if($db->num_rows() >= 1){
            while($r = mysqli_fetch_object($sql)){
              $sql2 = $db->query("SELECT * FROM `chance` WHERE `uid` = '$obj->id' AND `type` = '1' AND `option` = '$r->id'");
              if($db->num_rows() >= 1){
                $get2 = $db->fetch_object();
                $sjanse = $get2->percent.'%';
              }
              else{
                $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES('$obj->id','1','$r->id')");
                $sjanse = "0%";
              }
              echo '
              <tr class="valg notactive" onclick="sendpost('.$r->id.')">
              <td>'.htmlentities($r->handlingstekst, ENT_NOQUOTES | ENT_HTML401, "ISO-8859-1").'</td><td>'.number_format($r->minm).'-'.number_format($r->maxm).'</td><td>'.$r->wait.' sekunder</td><td>'.$sjanse.'</td>
              </tr>
              ';
            }
          }
          else{
            echo '
            <tr>
            <td colspan="4" style="text-align:center;"><i>Ingen handlinger er lagt til, be en admin legge til valg for deg. Bruk Support!</i></td>
            </tr>
            ';
          }
          ?>
        </table>
        <input type="hidden" value="" name="valget" id="valget">
      </form>
      <script language="javascript">
      function sendpost(valg) {
        $('#valget').val(valg);
        $('#krim').submit();
      }
      $(document).ready(function(){
        $('.valg').hover(function(){
          $(this).removeClass().addClass('c_2').css('cursor','pointer');
        },function() {
          $(this).removeClass().addClass('c_1').css('cursor','pointer');
        });
      });
      </script>
      <?php
    } 
  }
  endpage();