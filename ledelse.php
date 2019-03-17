<?php
  include("core.php");
  startpage("Ledelsen");
?>
<h1>Ledelsen</h1>
<table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
<tr>
<th>Administrator / Moderator</th><th>Sist aktiv:</th>
</tr>
</br>
<?php
    $sql = $db->query("SELECT * FROM `users` WHERE `status` IN('1','2') ORDER BY `status` ASC, `id` ASC");
    while($r = mysqli_fetch_object($sql)){
      $time = time() - $r->lastactive;
      if($r->status == 1){
        $st = "<span style=\"color:#0ff;font-weight:bold;\">Admin</span>";
      }
      else if($r->status == 2){
        $st = "<span style=\"color:lime;font-weight:bold;\">Moderator</span>";
      }
      else if($r->status == 3){
        $st = "<span style=\"color:yellow;font-weight:bold;\">Forum Moderator</span>";
      }
      $extra = ($r->named !== NULL) ? $r->named : null;
      echo '
      <tr>
          <td>'.user($r->id).'  <b>(</b>'.$st.$extra.'<b>)</b></td>
              <td><span id="luser'.$r->id.'"></span><script>teller('.$time.',"luser'.$r->id.'","false","opp");</script></td>
      </tr>
      ';
    }
?>
</table>
</br>
<table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
	<tr>
		<th colspan="2">Utnevnte Forum mods:</th>
	</tr>
	<?php
    $sql = $db->query("SELECT * FROM `users` WHERE `status` IN('3') ORDER BY `status` ASC, `id` ASC");
	if($db->num_rows() >= 1){
    while($r = mysqli_fetch_object($sql)){
    $time = time() - $r->lastactive;
    if($r->status == 1){
        $st = "<span style=\"color:#0ff;font-weight:bold;\">Admin</span>";
    }
    else if($r->status == 2){
        $st = "<span style=\"color:lime;font-weight:bold;\">Moderator</span>";
    }
    else if($r->status == 3){
        $st = "<span style=\"color:yellow;font-weight:bold;\">Forum Moderator</span>";
    }
    $extra = ($r->named !== NULL) ? $r->named : null;
    echo '
    <tr>
        <td>'.user($r->id).'  <b>(</b>'.$st.$extra.'<b>)</b></td>
            <td><span id="user'.$r->id.'"></span><script>teller('.$time.',"user'.$r->id.'","false","opp");</script></td>
    </tr>
    ';}
	}
	    else{
        echo '<tr><td colspan="2"><em>Ingen utnevnt som Forum-Moderator</em></td></tr>';
    }
?>
</table>
</br>
<table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
	<tr>
	</br>
		<th colspan="2">Utnevnte Picmakere:</th>
	</tr>
	<?php
    $sql = $db->query("SELECT * FROM `users` WHERE `status` IN('4') ORDER BY `status` ASC, `id` ASC");
	if($db->num_rows() >= 1){
    while($r = mysqli_fetch_object($sql)){
    $time = time() - $r->lastactive;
    if($r->status == 1){
        $st = "<span style=\"color:#0ff;font-weight:bold;\">Admin</span>";
    }
    else if($r->status == 2){
        $st = "<span style=\"color:lime;font-weight:bold;\">Moderator</span>";
    }
    else if($r->status == 4){
        $st = rainbow("Picmaker");
    }
    $extra = ($r->named !== NULL) ? $r->named : null;
    echo '
    <tr>
        <td>'.user($r->id).'  <b>(</b>'.$st.$extra.'<b>)</b></td>
            <td><span id="puser'.$r->id.'"></span><script>teller('.$time.',"puser'.$r->id.'","false","opp");</script></td>
    </tr>
    ';}
	}
	    else{
        echo '<tr><td colspan="2"><em>Ingen utnevnt som Picmaker.</em></td></tr>';
    }
	?>
	</table>
<table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
	<tr>
	</br>
		<th colspan="2">Utnevnte Supportspillere:</th>
	</tr>
	<?php
    $sql = $db->query("SELECT * FROM `users` WHERE `support` = '1' ORDER BY `status` ASC, `id` ASC");
	if($db->num_rows() >= 1){
    while($r = mysqli_fetch_object($sql)){
    $time = time() - $r->lastactive;
    if($r->status == 1){
        $st = "<span style=\"color:#0ff;font-weight:bold;\">Admin</span>";
    }
    else if($r->status == 2){
        $st = "<span style=\"color:lime;font-weight:bold;\">Moderator</span>";
    }
    else if($r->status == 6){
        $st = "<span style=\"color:#FF9600;font-weight:bold;\">Picmaker</span>";
    }
    else if($r->support == 1){
        $st = "<span style=\"color:#DE22D7;font-weight:bold;\">Supportspiller</span>";
    }
    $extra = ($r->named !== NULL) ? $r->named : null;
    echo '
    <tr>
        <td>'.user($r->id).'  <b>(</b>'.$st.$extra.'<b>)</b></td>
        <td><span id="suser'.$r->id.'"></span><script>teller('.$time.',"suser'.$r->id.'","false","opp");</script></td>
    </tr>
    ';}
	}
	    else{
        echo '<tr><td colspan="2"><em>Ingen utnevnt som supportspiller.</em></td></tr>';
    }
	?>
	</table>
	</br>
	</br>
	
	<?php
	endpage();
	?>
