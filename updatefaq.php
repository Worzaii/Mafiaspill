<?php
// Har såvidt begynt.
include("core.php");
startpage("FAQ Panel");
if(r1() || r2()){
  // scriptet
  $faq = $db->query("SELECT * FROM `faq`");
  ?>
<table style="width:70%"class="table">
  <tr>
    <th colspan="4">FAQ Panelet</th>
  </tr>
  <tr>
    <td>Tittel</td><td>Sist Endret Av</td><td>Sist Endret</td><td><a href="updatefaq.php?ny">Ny</a></td>
  </tr>
  <?php
  while($r = mysqli_fetch_object($faq)){
    echo '<tr><td>'.$r->tittel.'</td><td>'.user($r->who).'</td><td>'.date("H:i:s | d-m-Y",$r->time).'</td><td><a href="updatefaq.php?endre='.$r->id.'">Endre</a> - <a onclick="return confirm(\'Er du sikker?\')" href="updatefaq.php?slett='.$r->id.'">Slett</a></td></tr>';
  }
  ?>
</table>
<?php
if(isset($_GET['endre'])){
  $id = intval($_GET['endre']);
  if(!is_numeric($id)){
    echo '<p class="feil">Ugyldig ID!</p>';
  }else{
    $fastrompe = $db->query("SELECT * FROM `faq` WHERE `id` = '$id'");
    $stivpenis = $db->fetch_object($fastrompe);
    ?>
<form action="" method="POST">
  <table class="table">
    <tr>
      <td><textarea style="margin: 2px 2px 2px 130px;height: 146px;width: 350px;max-width: 463px;"name="innholdfaq"><?=$stivpenis->innhold?></textarea></td>
    </tr>
  </table>
  <input type="submit" name="sendfaq" value="Endre">
</form>
<?php
  }
}
}else{noaccess();}

endpage();
?>