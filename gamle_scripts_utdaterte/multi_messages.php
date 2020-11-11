<?php
  include("core.php");
if (!r1()) {
    startpage("Ingen tilgang");
    noaccess();
    endpage();
    die();
}
  startpage("Nye meldinger");
?>
<h1>Admin: Multimelding</h1>
<p>Send en enkel melding til alle noterte spillere</p>
<?php
$res = null;
if (isset($_POST['tuser']) && isset($_POST['theme']) && isset($_POST['smsen'])) {
    $til = $db->escape($_POST['tuser']);
    $tema = $db->escape($_POST['theme']);
    $mel = $db->escape($_POST['smsen']);
    if (strlen($til) <= 2) {
        $res = '<p class="feil">Brukernavnet oppgitt er for kort!</p>';
        $re = false;
    } else if (!getUser($til)) {
        $res = '<p class="feil">Brukeren ' . htmlentities($til) . ' finnes ikke i våre databaser, sjekk at du har skrevet riktig.</p>';
        $re = false;
    } else if (strlen($mel) <= 2) {
        $res = '<p class="feil">Meldingen din er for kort! Du må minst ha 3 tegn.</p>';
        $re = false;
    } else {
        $userto = getUser($til, 1);
        if ($db->query("INSERT INTO `mail2`(`tid`,`fid`,`title`,`message`,`time`) VALUES('$userto','$obj->id','$tema','$mel',UNIX_TIMESTAMP())")) {
            $res = '<p class="lykket">Meldingen har blitt sendt til ' . status($userto, 1) . '!</p>';
        }
    }
}
if (isset($re) && $re == false) {/*Feil returnerer verdiene trygt*/
    $re1 = ' value="'.htmlentities($til).'"';
    $re2 = ' value="'.htmlentities($tema).'"';
    $time = str_replace("<", "&lt;", $_POST['smsen']);
    $re3 = str_replace(">", "&gt;", $time);
}
echo <<<END
    $res
    <form method="post" action="{$_SERVER['PHP_SELF']}">
      <table class="table" style="width:90%;margin:10px auto;">
      <thead>
      <tr>
      <th>Send melding til en spiller</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td style="padding:0px;margin:0px auto;"><input$re1 type="text" placeholder="Brukernavnene. Eks: Werzaire,Slowboii,Mads" maxlength="20" name="tuser" style="width:100%;height:30px;text-indent: 10px;border:none;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;"></td>
      </tr>
      <tr>
      <td style="padding:0px;margin:0px auto;"><input$re2 type="text" maxlength="50" placeholder="Tema" name="theme" style="width:100%;height:30px;text-indent: 10px;border:none;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;"></td>
      </tr>
      <tr>
      <td>Tekst:</td>
      </tr>
      <tr>
      <td style="text-align:center;padding:0px; margin:0px;">
      <textarea placeholder="Skriv inn meldingen til spiller her. Minimum 3 tegn!" name="smsen" style="width:100%;border:none;height:100px;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;text-indent: 10px;">$re3</textarea>
      </td>
      </tr>
      <tr>
      <td style="text-align:center;"><input type="submit" value="Send melding!"></tr>
      </tbody>
      </table>
      </form>
END;
  endpage();
?>