<?php
  include("core.php");
  startpage("P&aring;loggede spillere");
?>
<h1>Sist p&aring;logget</h1>
<?php
  if(($obj->status == 1 || $obj->status == 2)){
    $add1 = "<td>IP-adresse</td>";
    $add3 = "<td>Hostname</td>";
    $cols = 4;
  }
  else{
    $add1 = null;
    $cols = 2;
  }
  //Her kommer skript for &aring; liste opp p&aring;loggede spillere
  $sql = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time()-1800)."' AND '".time()."' ORDER BY `lastactive` DESC");
?>
<table class="table">
    <tr>
        <th colspan="<?=$cols;?>">P&aring;logget n&aring;:</th>
    </tr>
    <tr>
        <td style="width:95px;">Spiller:</td>
        <td>Sist aktiv:</td><?=$add1.$add3;?>
    </tr>
    <?php
        while($r = mysqli_fetch_object($sql)){
          $state = null;
          $state2 = null;
          $newtime = time() - $r->lastactive;
          if($obj->status == 1 || $obj->status == 2){
            $add2 = "<td><span class=\"added-ip\">".(($r->ip != NULL) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->ip) : "Ikke registrert")."</span></td>";
            $add3 = "<td><span class\"\">".(($r->hostname != NULL) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->hostname) : "Ikke registrert")."</span></td>";
          }
          else{
            $add2 = null;
            $add3 = null;
          }
          echo '
          <tr>
          <td style="cursor:pointer;" onclick="javascript:window.location=\'profil.php?id='.$r->id.'\'">'.status($r->user).$state.$state2.'</td><td><span id="id'.$r->id.'"></span><script>teller('.$newtime.',\'id'.$r->id.'\',false,\'opp\');</script></td>'.$add2.$add3.'
          </tr>
          ';
          $state = null;//Resetter variabel om den fortsatt er aktiv ettersom det ikke gjelder alle.
          $state2= null;//Resetter variabel om den fortsatt er aktiv ettersom det ikke gjelder alle.
        }
        mysqli_free_result($sql);
    ?>
</table>
<?php
if(($obj->status == 1 || $obj->status == 2)){
    echo '<table class="table" style="margin-top: 15px;"><tr><th colspan="4">Spillere som har v&aelig;rt p&aring;logget siste 30 dager</th></tr><tr><th>Bruker</th><th>Sist aktiv</th><th>Ip</th><th>Hostname</th></tr>';
    $sql2 = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".strtotime("-30days")."' AND '".(time() - 1800)."' ORDER BY `lastactive` DESC")or die(mysql_error());
    while($r = mysqli_fetch_object($sql2)){
        $newtime = time() - $r->lastactive;
            $add2 = "<td>".(($r->ip != NULL) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->ip) : "Ikke registrert")."</td>";
            $add3 = "<td>".(($r->hostname != NULL) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->hostname) : "Ikke registrert")."</td>";
        echo '
        <tr>
        <td style="cursor:pointer;" onclick="javascript:window.location=\'profil.php?id='.$r->id.'\'">'.status($r->user).'</td><td><span id="idx'.$r->id.'"></span><script>teller('.$newtime.',\'idx'.$r->id.'\',false,\'opp\');</script></td>'.$add2.$add3.'
        </tr>
        ';
    }
    echo '</table>';
}
    endpage();
?>