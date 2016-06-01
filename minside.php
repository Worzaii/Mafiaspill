<?php
  include("core.php");
  startpage("Din side - Jobbes med");
  $rank = rank($obj->exp);
  $maks = $rank[3];
  $rnr=$rank[0];
  $prosent=($rank[2] / $maks)*100;
  if($rnr == 12){
    $rest="Du har nådd høyeste rank, gratulerer! :)";
  }
  else{
    $rest=$maks - $rank[2];
  }
?>
<h1>Min side</h1>
<table class="table" style="width:300px;">
  <tr>
    <td>Ipadresse:</td><td style="width:300px"><?=$_SERVER['REMOTE_ADDR']?></td>
  </tr>
  <tr>
    <td>Rank:</td><td style="width:300px"><?=$rank[1]?>(<?=$rank[0]?>)</td>
  </tr>
</table>
<?php
// En ineffektiv måte for å sjekke handlinger :P Don't mind it.
$fuck = $db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id'");
$krlyk = $db->num_rows($db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id' AND `resu` = '1'"));
$krfeil = $db->num_rows($db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id' AND `resu` = '0'"));
$fuckxto = $db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id'");
$fuckxtolyk = $db->num_rows($db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `resu` = '1'"));
$fuckxtofeil = $db->num_rows($db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `resu` = '0'"));
$fuckxtofeilto = $db->num_rows($db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `resu` = '2'"));
$ran = $db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id'");
$ransjekk = $db->fetch_object($ran);
$ranlyk = $db->num_rows($db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `kl` <> 0"));
$ranfeil = $db->num_rows($db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `kl` = '0'"));
$rantjent = $db->fetch_object($ranlyk);
?>
<br>
<table style="width:300px;" class="table">
  <tr>
    <th style="padding:1px;" colspan="2">Statistikk over lykket / feilet handlinger</th>
  </tr>
  <tr>
    <td>Krim: <?=$krlyk?> / <?=$krfeil?></td><td>Totalt gjennomført: <?=$db->num_rows($fuck)?></td>
    <tr><td>Biltyveri: <?=$fuckxtolyk?> / <?=$fuckxtofeil+$fuckxtofeilto?></td><td>Totalt gjennomført: <?=$db->num_rows($fuckxto)?></td></tr>
    <tr><td>Ran-Spiller: <?=$ranlyk?> / <?=$ranfeil?></td><td>Totalt gjennomførte: <?=$db->num_rows($ran)?></td></tr>
  </tr>
</table>

<h1>Rankbar</h1>
<div style="margin-left: auto;margin-right: auto;width: 550px;height: 50px">
  <div style="width:550px;margin-top:10px;text-align: center;height: 40px;background: #501;position: absolute;z-index: 1;border-radius: 16px;overflow: hidden;border: 2px solid #000;">
    <div style="background: #22d300;width: <?=number_format($prosent,1)?>%;height: 40px;border-radius: 0px;"></div>
  </div>
  <p style="padding:0;z-index: 2;position: relative;margin:0;top:22px;text-align: center"><?=number_format($prosent,3)?>%</p>
</div>
<p>Nåværende xp: <?=$obj->exp?><br />
Gjenværende xp i denne ranken: <?=$rest?></p>
<?
  endpage();
?>