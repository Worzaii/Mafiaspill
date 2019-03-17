<?php
include 'core.php';
$faces = array (
    "To"=>2, "Tre"=>3, "Fire"=>4, "Fem"=>5, "Seks"=>6, "Syv"=>7, "Åtte"=>8,
    "Ni"=>9, "Ti"=>10, "Knekt"=>10, "Dame"=>10, "Konge"=>10, "Ess"=>1
);
$tastatur=array("Ess"=>1,"To"=>2,"Tre"=>3,"Fire"=>4,"Fem"=>5,"Seks"=>6,"Syv"=>7,"Åtte"=>8,"Ni"=>9,"Ti"=>10,"Knekt"=>11,"Dame"=>12,"Konge"=>13);
function evaluateHand($hand) {
  global $faces;
  $value = 0;
  $hasEss = 0;
  foreach ($hand as $card) {
    if($card['face'] == 'Ess'){$hasEss = $hasEss + 1;}
    else{
      $value = intval($value) + intval($faces[$card['face']]);
    }
  }
  if($hasEss >= 1){
    for ($i = 0; $i < $hasEss; $i++) {
      if($value >= 11){
        $value=$value + 1;
      }
      else{
        $value = $value + 11;
      } 
    }
  }
  return $value;
}
if(false == true){
  startpage("Ingen tilgang!");
  noaccess();
}
else{
  startpage("Spilte bjrunder");
  ?>
<p>10 siste runder spilt:</p>
<?php
    $s = $db->query("SELECT * FROM `bjtables` ORDER BY `id` DESC LIMIT 0,10");
    if($db->num_rows() >= 1){
      echo '<table class="table">';
      while($a = mysqli_fetch_object($s)){
        echo '<tr><td><table class="table"><tr><td>'.user($a->uid).' med sum av '.evaluateHand(unserialize($a->ucards)).'</td><td>Dealeren med sum av '.evaluateHand(unserialize($a->dcards)).'</td></tr>'
          . '<tr><td>';
        foreach (unserialize($a->ucards) as $index => $card) {
          /*Henter fram bilder, og sorterer de etter kode*/
          if($card['suit'] == "Hjerter"){
            $img = '<img src="spillkort/h'.$tastatur[$card['face']].'.png" alt="Hjerter '.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Kløver"){
            $img = '<img src="spillkort/k'.$tastatur[$card['face']].'.png" alt="Kløver'.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Ruter"){
            $img = '<img src="spillkort/r'.$tastatur[$card['face']].'.png" alt="Ruter '.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Spar"){
            $img = '<img src="spillkort/s'.$tastatur[$card['face']].'.png" alt="Spar '.$tastatur['face'].'">';
          }
          echo $img;
        }
        echo '</td><td>';
        foreach (unserialize($a->dcards) as $index => $card) {
          /*Henter fram bilder, og sorterer de etter kode*/
          if($card['suit'] == "Hjerter"){
            $img = '<img src="spillkort/h'.$tastatur[$card['face']].'.png" alt="Hjerter '.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Kløver"){
            $img = '<img src="spillkort/k'.$tastatur[$card['face']].'.png" alt="Kløver'.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Ruter"){
            $img = '<img src="spillkort/r'.$tastatur[$card['face']].'.png" alt="Ruter '.$tastatur['face'].'">';
          }
          else if($card['suit'] == "Spar"){
            $img = '<img src="spillkort/s'.$tastatur[$card['face']].'.png" alt="Spar '.$tastatur['face'].'">';
          }
          echo $img;
        }
        echo '</td></tr>'
    . '</table></td></tr>';
      }
      echo '</table>';
    }
    else{
      feil("Det finnes ingen runder! Spill en runde!");
    }
  ?>
<?php
}
endpage();