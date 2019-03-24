<?php
include 'core.php';
if(!in_array($obj->id, array(1,2))){
  startpage("Ingen tilgang!");
  noaccess();
  endpage();
}
$stilling=array(1=>"Admin",2=>"Moderator",3=>"Forum-Moderator",4=>"Picmaker",5=>"Vanlig spiller");
startpage("Stillingslogg");
echo '<h1>Viser stillinger</h1><p>Her har du oversikt over hvem som ble satt til en stilling, og n&aring;r det ble gjort av hvem. Siste stilling satt er &oslash;verst.</p>';
?>
<table class="table">
  <thead>
    <tr>
      <th>#ID</th><th>Bruker</th><th>Stilling</th><th>Satt av</th><th>Dato</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $q = $db->query("SELECT * FROM `stillingslogg` ORDER BY `id` DESC");
      while($r = mysqli_fetch_object($q)){
        echo '<tr><td>'.$r->id.'</td><td>'.user($r->nyid).'</td><td>'.$stilling[$r->type].'</td><td>'.user($r->uid).'</td><td>'.date("H:i:s d.m.Y",$r->dato).'</td></tr>';
      }
    ?>
  </tbody>
</table>
<?php
  endpage();
?>