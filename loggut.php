<?php
session_name("Mafia-no");
session_start();
if(isset($_COOKIE['trigger'])){
  setcookie("trigger", NULL, -3600, '/', 'mafia-no.net', FALSE, TRUE);
}
session_destroy();
session_start();
if(isset($_GET['g'])){
  $g = $_GET['g'];
  if($g == 1){
    $_SESSION['grunn'] = '<p>Logg inn for å fortsette!</p>';
  }
  elseif ($g==2) {
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi databasen ikke var tilgjengelig, prøv igjen senere!</p>';
  }
  elseif ($g==3) {
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi det var noe som ikke helt stemte med din session!</p>';
  }
  else if($g==4){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi brukernavnet og passordet ikke lengre stemmer overens men innloggingsdata.</p>';
  }
  else if($g==5){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi du har vært inaktiv for lenge, logg inn igjen for å fortsette.</p>';
  }
  else if($g==6){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi en admin logget deg ut. Da er det mulig du har fortsatt å oppdatert siden i lengre perioder uten å lest melding mottat fra administratoren.</p>';
  }
  else if($g==7){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi noen andre logget inn på brukeren på en annen ip.<br /><span style="color:#c00">Om du mener at det er noen andre som har fått tak i ditt passord og logger inn hele tiden, resett ditt passord igjennom "glemt passord" så snart som overhodet mulig! Om de har fått kontroll over din email, ta kontakt på følgende emailadresse(du må kanskje opprette ny midlertidig email): <a href="mailto:baretester@live.no">baretester@live.no</a></span><br /></p>';
  }
  
}
else{
  $_SESSION['grunn'] = '<p>Du har nå logget deg ut! Velkommen igjen!</p>';
}
header("Location: /");/*Sender til innloggingssiden*/