<?php
define("BASEPATH", true);
include("./system/config.php");
session_unset();
session_destroy();
session_start();
if (isset($_GET['g'])) {
    $g = $_GET['g'];
    if ($g == 1) {
        $_SESSION['grunn'] = '<p class="info">Utlogget på grunn av inaktivitet over 30 minutter. 
Logg inn igjen for å fortsette...</p>';
    } else if ($g == 2) {
        $_SESSION['grunn'] = '<p class="feil">Du ble sendt hit fordi databasen ikke var tilgjengelig, 
prøv igjen senere!</p>';
    } else if ($g == 3) {
        $_SESSION['grunn'] = '<p class="warning">Du ble sendt hit fordi det var noe som 
ikke helt stemte med din session!</p>';
    } else if ($g == 4) {
        $_SESSION['grunn'] = '<p class="feil">Du ble sendt hit fordi brukernavnet og passordet ikke lengre 
stemmer overens i forhold til innloggingsdata.</p>';
    } else if ($g == 5) {
        $_SESSION['grunn'] = '<p class="info">Du ble sendt hit fordi du har vært inaktiv for lenge, 
logg inn igjen for å fortsette.</p>';
    } else if ($g == 6) {
        $_SESSION['grunn'] = '<p class="info">Du ble sendt hit fordi en admin logget deg ut. Da er det mulig du har 
fortsatt å oppdatert siden i lengre perioder uten å lest melding mottat fra administratoren.</p>';
    } else if ($g == 7) {
        $_SESSION['grunn'] = '<p class="feil">Du ble sendt hit fordi noen andre logget inn på brukeren på 
en annen ip.<br><span style="color:#c00">Om du mener at det er noen andre som har fått tak i ditt passord og 
logger inn hele tiden, resett ditt passord igjennom "glemt passord" så snart som overhodet mulig! 
Om de har fått kontroll over din email, ta kontakt på følgende 
emailadresse(du må kanskje opprette ny midlertidig email): <a href="mailto:' . HENVEND_MAIL . '">
' . HENVEND_MAIL . '</a></span><br></p>';
    } else if ($g == 8) {
        $_SESSION['grunn'] = '<p class="feil">Crossing er ikke tillatt, utlogget!</p>';
    }
} else {
    $_SESSION['grunn'] = '<p>Du har nå logget deg ut! Velkommen igjen!</p>';
}
header("Location: https://" . DOMENE_NAVN);
