<?php
include("core.php");
	startpage("Banken");
	if(fengsel() == true){
	echo '<h1>Bank</h1>
	<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
	<script type="text/javascript">
	teller('.fengsel(true).',\'krim\',true,\'ned\');
	</script>
	';
}
else if(bunker() == true){
	$bu = bunker(true);
	echo '<h1>Bank</h1>
	<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br />Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
	<script type="text/javascript">
	teller('.($bu - time()).',\'bunker\',false,\'ned\');
	</script>
	';
}
else{
  if(isset($_GET['til'])){
    $user = $_GET['til'];
    $tilhvem = 'value="'.htmlentities($user).'" ';
  }
  else{
    $tilhvem=NULL;
  }
?>
<h1>Banken</h1>
<?php
  if(isset($_POST['money'])){
    //Sjekker og utfører
    $money = $db->escape($_POST['money']);
    $money = str_replace(",","",$money);
    $money = str_replace(".","",$money);
    $money = str_replace(" ","",$money);
    $money = str_replace("kr","",$money);
    if($money == NULL){
      $money = 0;
    }
    if(!is_numeric($money) ){
      $ret = '<p class="feil">Ikke gyldig input, må kun bestå av tall, du kan inkludere mellomrom, punktum, komma og "kr".</p>';
    }
    else{
      if(isset($_POST['withdraw'])){
        if($_POST['alldo'] == 1){
          if($obj->bank <= 0){
            $ret = '<p class="feil">Du må ta ut mer enn 0kr.</p>';
          }
          else{
            $db->query("UPDATE `users` SET `hand` = (`hand` + `Bank`), `Bank` = '0' WHERE `id` = '$obj->id'");
            $db->query("INSERT INTO `banklogg`(`uid`,`sum`,`way`,`all`,`time`) VALUES('$obj->id','".$db->escape($_POST['money'])."','0','1',UNIX_TIMESTAMP())");
            $ret = '<p class="lykket">Du har tatt ut '.number_format($obj->bank).'kr!</p>';
          }
        }
        else{
          if($money <= 0){
            $ret = '
            <p class="feil">Du må ta ut mer enn 0 kr.</p>
            ';
          }
          else if($money >= 1){
            //Fortsetter validering
            if($money <= $obj->bank){
              if($db->query("UPDATE `users` SET `bank` = (`bank` - $money), `hand` = (`hand` + $money) WHERE `id` = '$obj->id'")){
                $db->query("INSERT INTO `banklogg`(`uid`,`sum`,`way`,`all`,`time`) VALUES('$obj->id','".$db->escape($_POST['money'])."','0','0',UNIX_TIMESTAMP())");
                $ret = '
                <p class="lykket">Du har tatt ut '.number_format($money).'kr fra banken din.</p>
                ';
              }
              else{
                //Spørring kunne ikke utføres
                $ret = '<p class="feil">Kunne ikke utføre spørringen: '.mysqli_error().'</p>';
              }
            }
            else{
              $ret = '<p class="feil">Du har ikke så mye penger i banken!</p>';
            }
          }
        }
      }//Ta ut end
      else if(isset($_POST['deposit'])){
        if($_POST['alldo'] == 1){
          if($obj->hand <= 0){
            $ret = '<p class="feil">Du må sette inn mer enn 0kr.</p>';
          }
          else{
            $db->query("UPDATE `users` SET `Bank` = (`Bank` + `hand`),`hand` = '0' WHERE `id` = '$obj->id'");
            $db->query("INSERT INTO `banklogg`(`uid`,`sum`,`way`,`all`,`time`) VALUES('$obj->id','".$db->escape($_POST['money'])."','1','1',UNIX_TIMESTAMP())");
            $ret = '<p class="lykket">Du har satt inn '.number_format($obj->hand).'kr!</p>';
          }
        }
        else{
          if($money <= 0){
            $ret = '
            <p class="feil">Du må sette inn mer enn 0 kr.</p>
            ';
          }
          else if($money >= 1){
            //Fortsetter validering
            if($money <= $obj->hand){
              if($db->query("UPDATE `users` SET `bank` = (`bank` + $money), `hand` = (`hand` - $money) WHERE `id` = '$obj->id'")){
                $db->query("INSERT INTO `banklogg`(`uid`,`sum`,`way`,`all`,`time`) VALUES('$obj->id','".$db->escape($_POST['money'])."','1','0',UNIX_TIMESTAMP())");
                $ret = '
                <p class="lykket">Du har satt inn '.number_format($money).'kr i banken din.</p>
                ';
              }
              else{
                //Spørring kunne ikke utføres
                $ret = '<p class="feil">Kunne ikke utføre spørringen: '.mysqli_error().'</p>';
              }
            }
            else{
              $ret = '
              <p class="feil">Du har ikke så mye penger ute på handa! Du har bare '.number_format($obj->hand).' kr.</p>
              ';
            }
          }
        }
      }//Sett inn end
    }//Post END
  }
  if(isset($_POST['tilover'])){
    $user = $db->escape($_POST['tilover']);
    $bank = $db->escape($_POST['sumover']);
    $bank = str_replace(",","",$bank);
    $bank = str_replace(".","",$bank);
    $bank = str_replace(" ","",$bank);
    if(is_numeric($bank)){
      if($bank >= 1){
        if($bank <= $obj->bank){
          $usin = $db->query("SELECT * FROM `users` WHERE `user` = '$user'");
          if($db->num_rows() == 1){
            $u = $db->fetch_object();
            if($u->id != $obj->id){
              if($db->query("UPDATE `users` SET `bank` = (`bank` - $bank) WHERE `id` = '$obj->id' LIMIT 1")){
                if($db->query("UPDATE `users` SET `bank` = (`bank` + $bank) WHERE `id` = '$u->id' LIMIT 1")){
                  $ret .= '<p class="lykket">Du har overført '.number_format($bank).'kr til '.$u->user.'!</p>';
                  $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$u->id."','".time()."','".$db->slash('--<b>Bank</b><br/>'.$obj->user.' har overført '.number_format($bank).'kr til deg!')."')");
                  $time = time();
                  $db->query("INSERT INTO `bankoverforinger`(`uid`,`tid`,`sum`,`time`) VALUES('$obj->id','$u->id','$bank','$time')");
                }
              }
              else{
                $ret .= '<p class="feil">Kunne ikke overføre pengene!</p>';
              }
            }
            else{
              $ret.='<p class="feil">Du kan ikke overføre penger til deg selv!</p>';
            }
          }
          else{
            $ret .= '<p class="feil">Personen du prøver å sende til eksisterer ikke!</p>';
          }
        }
        else{
        $ret .= '<p class="feil">Du har ikke så mye penger i banken!</p>';
        }
      }
      else{
      $ret .= '<p class="feil">Du må overføre mer enn 0 kr!</p>';
      }
    }
    else{
      $ret .= '<p class="feil">Du må oppgi en gyldig sum!</p>';
    }
  }
  if(isset($ret)){
    echo $ret;
  } ?>
<form method="post" action="">
  <table style="width:290px;" class="table bank">
    <thead>
      <tr>
        <th style="border-radius: 25px;" colspan="2">Dine verdier og muligheter:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Penger i banken:</td>
        <td><?php echo number_format($obj->bank); ?> kr</td>
      </tr>
      <tr>
        <td>Beløp</td>
        <td><input style="background-color:#aaa; border:1px; height:16px;"type="textbox" name="money" min="1"></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center;"><input style="font-size: 12px;border: 1px solid #aaa;padding: 7px;padding-left: 10px;padding-right: 10px;" type="submit" class="gjennomfør" value="Ta ut!" name="withdraw">&nbsp;&nbsp;<input style="font-size: 12px; border: 1px solid #aaa; padding: 7px; padding-left: 10px; padding-right: 10px;" type="submit" class="gjennomfør" value="Sett inn!" name="deposit">
    <input type="checkbox" name="alldo" value="1" style="margin-top: 29px;margin-left: 1px;">Alt?
        </td>
      </tr>
    </tbody>
  </table>
</form>
<form method="post" action="">
  <table style="width:290px;"class="table bank">
    <tr>
      <th style="border-radius: 25px;" colspan="2">Bankoverføringer:</th>
    </tr>
    <tr>
      <td>Bruker:</td>
      <td><input style="background-color:#aaa; border:1px; height:16px;" <?=$tilhvem;?>type="text" name="tilover" maxlength="16" /></td>
    </tr>
    <tr>
      <td>Sum:</td>
      <td><input style="background-color:#aaa; border:1px; height:16px;" type="text" name="sumover" /></td>
    </tr>
    <tr>
      <th colspan="2"><input style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" class="gjennomfør" type="submit" value="Overfør pengene!" /></th>
    </tr>
  </table>
</form>
<table style="width:510px;"class="table bank">
  <tr>
    <th style="border-radius: 25px;" colspan="4">Bankoverføringer fra og til deg (maks 20 visninger)</th>
  </tr>
  <tr>
    <th>Fra</th>
    <th>Til</th>
    <th>Sum:</th>
    <th>Tid:</th>
  </tr>
  <?php
    $fe = $db->query("SELECT * FROM `bankoverforinger` WHERE `uid` = '$obj->id' OR `tid` = '$obj->id' ORDER BY `id` DESC LIMIT 20");
    while($r = mysqli_fetch_object($fe)){
      echo '
      <tr>
      <td>'.user($r->uid).'</td><td>'.user($r->tid).'</td><td>'.number_format($r->sum).'kr</td><td>'.date("H:i:s | d-m-Y",$r->time).'</td>
      </tr>
      ';
    } ?>
</table>
<?php
	}
	endpage();
?>