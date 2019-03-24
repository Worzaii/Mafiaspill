<?php
include 'core.php';
//include './pagination.php';
if(r1()){
  startpage("Innloggingsliste");
  echo '<h1>Viser Innloggingsantallet per bruker</h1>';
  echo '<p>Under her vises det en tabell der de brukerne med flest innlogginger '
  . 'blir vist &oslash;verst, og de med f&aelig;rre nedover. '
  . 'For &aring; finne en spesifikk bruker, skriv inn i tekstfeltet og trykk enter(linjehopp).</p>';
  $q = $db->query("SELECT `uid`,COUNT(*) AS `sum` FROM `sessusr` GROUP BY `uid` ORDER BY COUNT(*) DESC LIMIT 0,40");
//  $pag = new Pagination($db, $q, 40, "s");
  echo '<p>Antall rader &aring; vise: '.$db->num_rows().'</p>';
  if($db->num_rows() >= 1){
    echo '<table class="table" style="width:200px;"><thead><tr><th>Brukernavn:</th><th>Innlogginger</th></tr></thead>';
    while($r = mysqli_fetch_object($q)){
      echo '<tr><td>'.user($r->uid).'</td><td>'.$r->sum.'</td></tr>';
    }
    echo '</table>';
    mysqli_free_result($q);
  }
  else{
    echo '<p class="feil">Merkelig, det er ingen innlogginger &aring; vise :S</p>';
    echo mysqli_error($db->connection_id);
  }
}
else{
  startpage("Ingen tilgang!");
  noaccess();
}
endpage();
