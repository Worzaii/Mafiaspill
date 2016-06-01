<?php
include("core.php");
if(r1() || r2()){//Denne gjør slik at kun admin og moderator kan se innholdet i filen.(se siden i html) Hvis du skriver /poker.php i nettleseren får du en rød trekant :P
$suits = array (
  "Spar", "Hjerter", "Kl&oslash;ver", "Ruter"
);
$faces = array (
  "To"=>2, "Tre"=>3, "Fire"=>4, "Fem"=>5, "Seks"=>6, "Syv"=>7, "&Aring;tte"=>8,
  "Ni"=>9, "Ti"=>10, "Knekt"=>10, "Dame"=>10, "Konge"=>10, "Ess"=>1
);
$deck = array();
foreach ($suits as $suit) {
  $keys = array_keys($faces);
  foreach ($keys as $face) {
    $deck[] = array('face'=>$face,'suit'=>$suit);
  }
}
shuffle($deck);/*Randomiserer kortene i kortstokken*/
$hand = array();
startpage("Poker");
?>
<h1>Poker</h1>
<p>Spill poker, det annerkjente kortspillet der de alle lever ville liv i håp om å få Royal Flush!<br>Minstebeløp er på 10,000kr, maks er på 500,000,000kr!</p>
<?php
  /*Sjekker for eksisterende runde*/
  $poker = $db->query("SELECT * FROM `pokertables` WHERE `uid` = '{$obj->id}' AND `round` = '0' ORDER BY `id` DESC LIMIT 0,1");
  if(!$poker){
    feil("Tabellen eksisterer ikke eller det var noe feil med spørringen, ta kontakt med support!");
    endpage();
  }
  if($db->num_rows() == 1){
    /*Runde eksisterer, henter den opp og gir brukeren valg + viser kort osv*/
    $r = $db->fetch_object();
    ?>
<table class="table">
  <thead>
    <tr>
      <th>Innsats: <?=number_format($r->bet)?>kr</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <?php
        $ucards = unserialize($r->ucards);
        foreach($ucards as $index => $card){
          /*Skriver ut kortene slik at spilleren kan se dem*/
          if($card['suit'] == "Hjerter"){
            $img = '<img src="spillkort/h'.$faces[$card['face']].'.png" alt=" '.$faces['face'].'" title="'.$faces[$card['face']].'">';
          }
          else if($card['suit'] == "Kløver"){
            $img = '<img src="spillkort/k'.$faces[$card['face']].'.png" alt="Kløver'.$faces['face'].'" title="'.$faces[$card['face']].'">';
          }
          else if($card['suit'] == "Ruter"){
            $img = '<img src="spillkort/r'.$faces[$card['face']].'.png" alt="Ruter '.$faces['face'].'" title="'.$faces[$card['face']].'">';
          }
          else if($card['suit'] == "Spar"){
            $img = '<img src="spillkort/s'.$faces[$card['face']].'.png" alt="Spar '.$faces['face'].'" title="'.$faces[$card['face']].'">';
          }
          echo $img;
        }
        ?>
      </td>
    </tr>
  </tbody>
</table>
<?php
  }
  else{
    /*Runde er ikke satt.*/
    if(isset($_POST['verdi'])){
      /*Starter poker, hvis spiller har råd*/
        /*Starter validering av input*/
      $bet = $_POST['verdi'];
      /*Fjerner først vanlige ekstrafidelideier*/
      $bet = str_replace(array('kr',',','.'),NULL,$bet);
      if(!is_numeric($bet)){
        feil("Det var ikke tall!");
      }
      else{
        if($bet >= 10000 && $bet <= 500000000){
          /*Setter opp nytt bord til spilleren og legger det inn i databasen*/
          for($i = 0;$i<5;$i++){
            $ucards[] = array_shift($deck);/*Henter ut kortene til spilleren fra kortstokken*/
          }
          $qu = $db->query("INSERT INTO `pokertables`(`id`,`uid`,`ucards`,`rest`,`round`,`time`,`bet`,`result`) "
            . "VALUES(NULL,'{$obj->id}','".serialize($ucards)."','".serialize($deck)."','0',UNIX_TIMESTAMP(),'".$db->escape($bet)."',NULL)");
            if(!$qu){
              feil('Det hendte en feil ved denne spørringen: '.$db->last_query.'<br>'.  mysql_errno($db->connection_id).' '.mysqli_error($db->connection_id));
            }
            else{
              lykket("Du har startet en runde!");
            }
        }
        else{
          feil('Du kan ikke by mer eller mindre enn 10,000->500,000,000kr! Du prøvde å by '.number_format(htmlentities($bet)));
        }
      }
    }
    /*Viser panel for pengesats og informasjon*/
    echo '
      <form method="POST" action="">
        <table class="table">
          <thead>
            <tr>
              <th>Poker</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><p>Hvor mye er du villig til å satse for?</p><input type="text" name="verdi"><br /><input type="submit" value="Start runde!"></td>
            </tr>
          </tbody>
        </table>
      </form>';
    }/*Runde er ikke igangsatt Else END*/
  }/*Admin/mod END*/
  else{
    startpage("Ingen tilgang!");
    noaccess();
  }
  endpage();