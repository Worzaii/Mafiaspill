<?php
    include("core.php");
    startpage("Supportpanel");
    if(r1() || r2() || $obj->support == 1){
?>
<h1 style="text-align:center;">Supportpanel</h1>
<?php
    if(isset($_GET['ny'])){
        if(isset($_POST['nybeskjed'])){
            $bes = $db->escape($_POST['nybeskjed']);
            $spiller = $db->escape($_POST['til']);
            $get = $db->query("SELECT * FROM `users` WHERE `user` = '$spiller'")or die(mysql_error());
            $tittel = $db->escape($_POST['tittel']);
            if($db->num_rows() == 0){
                echo '<p style="color:#f00;text-align:center;">Spilleren finnes ikke. <b>Melding ikke sendt!</b></p>';
            }
            else{
                $nyid = $db->fetch_object();
                if($db->query("INSERT INTO `supportpanel`(`fra_bruker`,`suptil_bruker`,`supmeld_tittel`,`supmeld_innhold`,`supmeld_dato`) VALUES('$obj->id','$nyid->id','$tittel','$bes','$dato')")){
                    echo '<p style="color:#0f0;text-align:center;">Du har sendt en melding til <b>'.$spiller.'</b></p>';
                }
								else{
									echo '<p class="feil">Feil med query; Kode '.mysqli_errno($db->connection_id).': '.mysqli_error($db->connection_id).'</p>';
								}
            }
        }
        if(isset($_GET['nick'])){
        //Forhåndsbestemt nick til pm
        $til = 'value="'.$db->escape($_GET['nick']).'"';
        }
        else{
        $til = null;
        }
        echo <<<ENDHTML
        <p><a href="supportpanel.php">Tilbake til innboks!</a></p>
        <form method="post" action="">
        <center>
        <p>Til: <input type="text" $til maxlength="20" name="til">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tittel: <input type="text" maxlength="20" name="tittel"></p>
        <p><textarea cols="65" rows="10" name="nybeskjed"></textarea></p>
        <input type="submit" value="Send melding!">
        </center>
        </form>
ENDHTML;
    }//Ny melding END
    else if(isset($_GET['vis'])){
        $vis = $db->escape($_GET['vis']);
          $sql = $db->query("SELECT * FROM `support` WHERE `id` = '".$vis."'");
        if($db->num_rows() == 0){
        	echo '<p style="color:#f00;text-align:center;">Denne meldingen er ikke til deg, har blitt slettet, finnes ikke eller er lukket.</p>';
        }
        else{
        $r = $db->fetch_object($sql);
        if(isset($_POST['smsback'])){
          $sms = $db->escape($_POST['smsback']);
          if(strlen($sms) <= 1){
            echo '<p class="feil">Du må ha en meldingstekst lengre enn et tegn.</p>';
            $keep = (strlen($sms)<=1) ? $sms : null;
          }
          if($r->besvart == 1){
            echo '<p class="feil">Denne meldingen er allerede besvart!</p>';
          }
          else{
            if($db->query("INSERT INTO `mail2`(`supportid`,`tid`,`fid`,`title`,`message`,`time`) VALUES('{$r->id}','$r->usid','$obj->id','Support svar: $r->theme','$sms',UNIX_TIMESTAMP())")){
              echo '<p class="lykket">Meldingen har blitt sendt!</p>';
              $db->query("UPDATE `support` SET `read` = '1' WHERE `id` = '{$r->id}' LIMIT 1");
              $db->query("UPDATE `support` SET `besvart` = '1' WHERE `id` = '{$r->id}' LIMIT 1");
              $db->query("UPDATE `users` SET `sendtmld` = (`sendtmld` + 1) WHERE `id` = '$obj->id'");
              $db->query("UPDATE `support` SET `besvartav` = {$obj->id} WHERE `id` = '{$r->id}' LIMIT 1") or die (mysqli_error($db->connection_id));
            }
            else{
              if($obj->status == 1){
                echo '<p class="feil">Feil ved sending av melding: <br>'.$db->last_error.'</p>';
              }
              else{
                echo '<p class="feil">Det har oppstått et problem ved sending av meldingen. Rapporter dette til en Administrator.</p>';
              }
            }
          }
        }
        if(isset($_GET['lest'])){
          $tab = $_GET['lest'];
          if($tab == 1){
          $db->query("UPDATE `support` SET `read` = '1' WHERE `id` = '{$r->id}' LIMIT 1");
            echo '<p class="lykket">Du har satt denne meldingen som lest!</p>';
          }
          else{
            echo 'Noe galt skjedde. Kontakt admin!';
          }
        }
        $fra = status($r->usid,1);
				$dato = date("H:i:s d.m.Y",$r->time);
				$textout = bbcodes($r->text,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0);
        $svaret = $db->query("SELECT * FROM `mail2` WHERE `supportid` = '{$r->id}'");
        $textin = $db->fetch_object($svaret);
        echo'
        <table class="table">
        <tr>
        <th colspan="2">Tema: '.$r->theme.'</th>
        </tr>
        <tr>
        <td>Mottat: '.$dato.'</td><td>Fra: '.$fra.'</td>
        </tr>
        <tr>
        <td colspan="2"><b>Melding:</b> <br />'.$textout.'</td>
        </tr>
        </table>
				<form method="post" action="">
					<table class="table">
						<thead>
							<tr>
								<th>Besvar supportmeldingen:</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:center;"><textarea style="width:100%;height:230px;color:#aaa;background-color:#333;border:0px;" name="smsback" placeholder="Skriv svaret her">'.htmlentities($textin->message,NULL,"ISO-8859-1").'</textarea><br /><input type="submit" value="Besvar meldingen!"></td>
                                                              ';
        if($r->read == 1){
            \NULL;} 
           else{
               echo '<a href="supportpanel.php?vis='.$r->id.'&lest=1"><b>Sett denne meldingen som lest!</b></a></br>';}
       $bes = $db->query("SELECT * FROM `support` WHERE `id` = {$r->id}");
       $besav = $db->fetch_object();
       if($r->besvart == 1){echo '<p>Denne meldingen ble besvart av: '.user($besav->besvartav).'</p>';}else NULL;
       echo '
							</tr>
						</tbody>
					</table>
				</form>
';
        }
    }
    else{
      /*Viser alle henvendelser*/
      function check($txt){
        if(strlen($txt) <= 0){
          $txt = 'Uten tittel';
        }
        return($txt);
      }
      echo '
      <table class="table">
      <tr>
      <th colspan="5">Meldinger:</th>
      </tr>
      <tr>
      <th>Tittel</th><th>Sendt fra</th><th>Dato</th><th>Besvart av</th>
      </tr>
      ';

      if($obj->status == 1 || $obj->status == 2 || $obj->support == 1){
        $getmes = $db->query("SELECT * FROM `support` WHERE `slettet` = '0' ORDER BY `id` DESC");
      }
      else{
          $getmes = $db->query("SELECT * FROM `support` WHERE `til_hvem` = '$obj->id' AND `slettet` = '0' ORDER BY `id` DESC");
      }
      if($db->num_rows($getmes) == 0){
        echo '<tr><td colspan="4" align="center">Ingen meldinger mottat!</td></tr>';
      }
      else{
        while($r = mysqli_fetch_object($getmes)){
        $ny1 = ($r->read == 1) ? NULL : '<b style="color:#FFFFFF"> (NY!)</b>';
        $bes = $db->query("SELECT * FROM `support` WHERE `id` = {$r->id}");
        $besav = $db->fetch_object();
        $besvart = ($r->besvart == 1) ? user($besav->besvartav) : 'Ikke besvart';
        echo '
        <tr>
        <td><a href="supportpanel.php?vis='.$r->id.'">'.$ny1.check($r->theme).'</a></td><td>'.user($r->usid).'</td><td>'.date("H:i:s d.m.y",$besav->time).'</td><td>'.$besvart.'</td>
        </tr>
        ';
        }
      }
      echo '</table>';
    }
    }
    else{
      noaccess();
    }
    endpage();