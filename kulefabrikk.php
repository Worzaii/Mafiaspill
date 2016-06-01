<?php
include 'core.php';
startpage("Kulefabrikk");
if($obj->family != NULL){
  $f = $db->query("SELECT * FROM `familier` WHERE `id` = '$obj->family'");
  $res = $db->fetch_object();
  echo '<h1>Kulefabrikk</h1><p>Her kommer kulefabrikken snart! :D</p>';
  if($res->Leder == $obj->id){
    /*Kan administrere kulefabrikk eller kjøpe*/
    $k = $db->query("SELECT * FROM `kulefabrikker` WHERE `mobowner` = '$obj->family'");
    if($db->num_rows() == 1):
      /*Viser frem kontrollpanel for kulefabrikken*/
      
    else:
      if(isset($_POST['kf'])){
        $r = $db->escape($_POST['kf']);
        $q = $db->query("SELECT * FROM `kulefabrikker` WHERE `id` = '$r' AND `active` = '0'");
        if($db->num_rows() == 1){
          echo 'Fant raden!';
        }
      }
      ?>
<form method="post" action="">
  <table style="width:300px;" class="table">
    <thead>
      <tr>
        <th colspan="3">Kulefabrikker</th>
      </tr>
      <tr>
        <th>Hvor</th><th>Eies av</th><th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $n = mysqli_query($db->connection_id,"SELECT * FROM `kulefabrikker`");
        $ir = 0;
        $re = array();
        while($r = mysqli_fetch_assoc($n)){
          array_push($re,$r);
          $ir++;
        }
        ?>
      <tr>
        <td>Kulefabrikken i <?=city($re[0]["city"]);?></td><td><?php echo ($re[0]["mobowner"] == 0) ? '<span style="color:#0f0">Åpen for kjøp!</span>' : ((famidtoname($re[0]["mobowner"], 1) != false) ? famidtoname($re[0]["mobowner"], 1) : '<span style="color:#f00">Død familie</span>');  ?></td><td><input<?php echo ($r1["active"] == 1) ? " disabled='disabled'" : NULL; ?> type="radio" value="1" name="kf"></td>
      </tr>
      <tr>
        <td>Kulefabrikken i <?=city($re[1]["city"]);?></td><td><?php echo ($re[1]["mobowner"] == 0) ? '<span style="color:#0f0">Åpen for kjøp!</span>' : ((famidtoname($re[1]["mobowner"], 1) != false) ? famidtoname($re[1]["mobowner"], 1) : '<span style="color:#f00">Død familie</span>');  ?></td><td><input<?php echo ($r1["active"] == 1) ? " disabled='disabled'" : NULL; ?> type="radio" value="1" name="kf"></td>
      </tr>
      <tr>
        <td>Kulefabrikken i <?=city($re[2]["city"]);?></td><td><?php echo ($re[2]["mobowner"] == 0) ? '<span style="color:#0f0">Åpen for kjøp!</span>' : ((famidtoname($re[2]["mobowner"], 1) != false) ? famidtoname($re[2]["mobowner"], 1) : '<span style="color:#f00">Død familie</span>');  ?></td><td><input<?php echo ($r1["active"] == 1) ? " disabled='disabled'" : NULL; ?> type="radio" value="1" name="kf"></td>
      </tr>
      <tr>
        <td>Kulefabrikken i <?=city($re[3]["city"]);?></td><td><?php echo ($re[3]["mobowner"] == 0) ? '<span style="color:#0f0">Åpen for kjøp!</span>' : ((famidtoname($re[3]["mobowner"], 1) != false) ? famidtoname($re[3]["mobowner"], 1) : '<span style="color:#f00">Død familie</span>');  ?></td><td><input<?php echo ($r1["active"] == 1) ? " disabled='disabled'" : NULL; ?> type="radio" value="1" name="kf"></td>
      </tr>
      <tr>
        <td>&nbsp;</td><td style="text-align: center"><input style="margin:0;" type="submit" value="Kjøp!"></td>
      </tr>
    </tbody>
  </table>
</form>
    <?php
    endif;
  }
  elseif($res->Ub == $obj->id){
    /*Viser oversikt for kulefabrikk, om det er noen*/
    $k = $db->query("SELECT * FROM `kulefabrikker` WHERE `mobowner`");
    if($db->num_rows() == 1){
      /*Viser oversikt over familiens kulefabrikk*/
    }
    else{
      feil("Familien eier ingen kulefabrikk!");
    }
  }
}
else{
  noaccess();
}
endpage();