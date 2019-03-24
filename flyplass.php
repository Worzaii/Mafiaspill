<?php
  include("core.php");
  startpage("Flyplass");
  echo '<img src="imgs\flyplass.png">';
?>
<h1>Flyplass</h1>
<?php
if(bunker() == true){
  $bu = bunker(true);
  echo '
  <p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
  <script>
  teller('.($bu - time()).',\'bunker\',false,\'ned\');
  </script>
  ';
}
else if(fengsel()){
  $ja = fengsel(true);
  echo '
  <p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.$ja.'</span></p>
  <script>
  teller('.$ja.',\'krim\',true,\'ned\');
  </script>
  ';
}
else{
  if($obj->airportwait > time()){
    echo '<p class="feil">Du m&aring; vente f&oslash;r du kan fly igjen! <span id="flyplass"></span><script>teller('.($obj->airportwait - time()).',"flyplass",false,"ned")</script></p>';
  }
  else{
  if(isset($_POST['tilby']))
  {
    $i = $db->escape($_POST['tilby']);
    if($i >= 1 && $i <= 8)
    {
      if($obj->city == $i){
        echo '<p class="feil">Du er allerede i denne byen!</p>';
      }
      else{
        if($obj->hand <= 9999){
          echo '<p class="feil">Du har ikke r&aring;d til &aring; fly!</p>';
        }
        else if($obj->hand >= 10000){
          $db->query("UPDATE `users` SET `hand` = (`hand` - 10000),`city` = '$i',`airportwait` = (UNIX_TIMESTAMP() + 600) WHERE `id` = '{$obj->id}' LIMIT 1");
          if($db->affected_rows() == 1){
            echo '<p class="lykket">Du har betalt for en billett til '.city($i).' til prisen av 10,000kr! Du m&aring; n&aring; vente i 20 minutter f&oslash;r du kan reise igjen.</p>';
            $db->query("UPDATE `firma` SET `Konto` = (`Konto` + 10000) WHERE `By` = '$i' AND `Type` = '2' LIMIT 1");
          }
          else{
            echo '<p class="feil">Du kunne ikke reise p&aring; grunn av en feil i enten sp&oslash;rring eller i databasen, ta kontakt med Ledelsen!</p>';
          }
        }
      }
    }
    else{
      echo '<p class="feil">Den byen du valgte, om du valgte noen, er ikke gyldig!</p>';
    }
  }
?>
<p>&aring; ta fly vil koste deg 10,000kr ingame. Dette blir endret i fremtiden slik at firmahaverne kan endre prisene mellom 1,000kr-50,000kr!</p>
<form method="post" action id="fly">
  <table class="table flyplass">
    <tr>
      <th><em title="Flyplass">Velg by:</em></th>
    </tr>
    <?php
      for($i=1;$i<=8;$i++)
      {
    echo '
    <tr class="valg" onclick="sendpost('.$i.')">
    <td>Reis til '.city($i).'!</td>
    </tr>
    ';
      }
    ?>
  </table>
  <input type="hidden" id="vei" value="0" name="tilby">
</form>
<script>
  $(function() {
    $( "#dialog-confirm" ).dialog({
      resizable: true,
      height:400,
      modal: true,
      buttons: {
        "Delete all items": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  });
  </script>
<script language="javascript">
function sendpost(valg) {
$('#vei').val(valg);
//$('.wantto').dialog();
$('#fly').submit();
} 

$(document).ready(function(){
$('.valg').hover(function(){
    $(this).find("td").removeClass().addClass('normrad1').css('cursor','pointer');
},function() {
    $(this).find("td").removeClass();
});
});   
</script>
<style type="text/css">
  .valg{
    cursor:pointer;
  }
</style>
<?php
}
}
endpage();
?>