<?php
include("core.php");
if(50 + 50 == 100){
  startpage("Ingen tilgang");
  noaccess();
  endpage();
  die();
}
else{
  if(isset($_POST['user'])){
    $s = $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($_POST['user'])."' LIMIT 1");
    if($db->num_rows() == 1){
      $old = $_SESSION['sessionzar'];
      $f = $db->fetch_object();
      unset($_SESSION['sessionzar']);
      $_SESSION['sessionzar'] = array($f->user,$f->pass,safegen($f->user,$f->pass));
      $db->query("UPDATE `users` SET `lastactive` = '".time()."',`ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '{$f->id}' AND `pass` = '{$f->pass}'")or die(mysqli_error($db->connection_id));
      if($db->affected_rows() != 1){
        $_SESSION['sessionzar'] = $old;
        $res="<p>Kunne ikke oppdatere db; ".mysqli_error($db->connection_id)."</p>";
      }
      else{
        //header("Location: /Nyheter");
        $res = "<p>Du er logget inn som ".$f->user."</p>";
      }
    }
    else{
      //Ikke ny bruker
      $res = "<p>Bruker eksisterer ikke!</p>";
    }
  }
  startpage("Vis som");
?>
  <h1>Vis side som en annen spiller</h1>
<?php
  if(isset($res)){
    echo $res;
  }
?>
<p>Du kan ikke ranke, men vise alt spilleren selv kan se.<br>Meldinger, bank osv.</p>
<form method="post" action="">
    <input type="text" name="user">
    <input type="submit">
</form>
<?php
}
endpage();