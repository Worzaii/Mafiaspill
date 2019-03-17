<?php
include("core.php");
startpage("Endre Spiller");
if(r1()){
?>
<form action="" method="GET">
  <table class="table">
    <tr><th colspan="2">Skriv inn spillernavn</th></tr>
    <tr><td><input type="text"<?=((isset($_GET['u'])) ? ' value="'.htmlentities($_GET['u']).'"' : NULL);?> name="s"/></td><td><input type="submit"/></td></tr>
  </table>
</form>
<?php
if(isset($_GET['s'])){
  $user = $db->escape($_GET['s']);
  $query = $db->query("SELECT * FROM `users` WHERE `user` = '$user' LIMIT 1");
  $fetch = $db->fetch_object();
  if($db->num_rows() == 1){
    if(isset($_POST['oppdater'])){
      $bank = (is_numeric($_POST['bank']) ? $_POST['bank'] : preg_replace("![^0-9]+!","$1",$_POST['bank']));
      $hand = $db->escape($_POST['hand']);
      $poeng = $db->escape($_POST['poeng']);
      $expp = $db->escape($_POST['exp']);
      $livv = $db->escape($_POST['liv']);
      $kulerr = $db->escape($_POST['kuler']);
      $profilen = $db->escape($_POST['profil']);
      $db->query("INSERT INTO `userschangelogg`(`id`, `uid`, `mid`, `bank_org`, `bank_new`, `hand_org`, `hand_new`, `points_org`, `points_new`, `exp_org`, `exp_new`, `health_org`, `health_new`, `bullets_org`, `bullets_new`, `profile_org`, `profile_new`) VALUES (NULL,'{$fetch->id}','{$obj->id}','{$fetch->bank}','{$bank}','{$fetch->hand}','{$hand}','{$fetch->points}','{$poeng}','{$fetch->exp}','{$expp}','{$fetch->health}','{$livv}','{$fetch->bullets}','{$kulerr}','{$fetch->profile}','{$profilen}')");
      if($db->query("UPDATE `users` SET 
        `bank` = '$bank',
        `hand` = '$hand',
        `points` = '$poeng',
        `exp` = '$expp',
        `health` = '$livv',
        `bullets` = '$kulerr',
        `profile` = '$profilen' WHERE `user` = '$user' LIMIT 1")){
         echo '<p class="lykket">'.htmlentities($user).' ble oppdatert!</p>';
         $fetch->bank=$bank;
         $fetch->hand=$hand;
         $fetch->points = $poeng;
         $fetch->exp=$expp;
         $fetch->health=$livv;
         $fetch->bullets=$kulerr;
         $fetch->profile=$profilen;
      }
    }
    /*Oppdater fetch manuelt*/
      $ttt = array("<span class='stat1'><b>Administrator</b></span>","<span class='stat2'>Moderator</span>","<span class='stat3'>Forum Moderator</span>","Picmaker","<span style=\"color:#fff;\">Vanlig spiller</span>","<span style=\"color:#FF9600;\">Død</span>");
      $status = $ttt[($fetch->status -1)];
    ?>
<form action="" method="POST">
  <table class="table">
    <tr><th colspan="2"><?=$fetch->user?></th></tr>
    <tr><td>Brukernavn</td><td><?=$fetch->user?></td></tr>
    <tr><td>ID</td><td>#<?=$fetch->id?></td></tr>
    <tr><td>Bank</td><td><input style="text-align: right" type="text" name="bank" value="<?=number_format($fetch->bank)?>">kr</td></tr>
    <tr><td>Hånd</td><td><input style="text-align: right" type="text" name="hand" value="<?=number_format($fetch->hand)?>">kr</td></tr>
    <tr><td>Poeng</td><td><input style="text-align: right" type="text" name="poeng" value="<?=number_format($fetch->points)?>"></td></tr>
    <tr><td>Exp</td><td><input style="text-align: right" type="text" name="exp" value="<?=$fetch->exp?>"></td></tr>
    <tr><td>Liv</td><td><input style="text-align: right" type="text" name="liv" value="<?=$fetch->health?>">%</td></tr>
    <tr><td>Kuler</td><td><input style="text-align: right" type="text" name="kuler" value="<?=number_format($fetch->bullets)?>"></td></tr>
    <tr><td>Status</td><td><?=$status?></td></tr>
    <tr><td>Profil</td><td><textarea style="background-color:#aaa;width: 490px;height: 300px;" type="text" name="profil"><?=$fetch->profile?></textarea></td></tr>
    <tr><td><input type="submit" name="oppdater"></td></tr>
  </table>
  <input type="hidden" name="sender" value="<?=$_POST["sender"]?>"/>
</form>
<?php
  }
  else{
    feil("Spilleren finnes ikke! Prøv igjen");
  }
}
endpage();
}
else{
  noaccess();
  endpage();
}