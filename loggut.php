<?php
define("BASEPATH", true);
include("./system/config.php");
session_unset();
session_destroy();
session_start();
if (isset($_GET['g'])) {
    $g = $_GET['g'];
    if ($g == 1) {
        $_SESSION['grunn'] = '<p>Utlogget p&aring; grunn av inaktivitet over 30 minutter. Logg inn igjen for &aring; fortsette...</p>';
    } elseif ($g == 2) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi databasen ikke var tilgjengelig, pr&oslash;v igjen senere!</p>';
    } elseif ($g == 3) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi det var noe som ikke helt stemte med din session!</p>';
    } else if ($g == 4) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi brukernavnet og passordet ikke lengre stemmer overens i forhold til innloggingsdata.</p>';
    } else if ($g == 5) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi du har v&aelig;rt inaktiv for lenge, logg inn igjen for &aring; fortsette.</p>';
    } else if ($g == 6) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi en admin logget deg ut. Da er det mulig du har fortsatt &aring; oppdatert siden i lengre perioder uten &aring; lest melding mottat fra administratoren.</p>';
    } else if ($g == 7) {
        $_SESSION['grunn'] = '<p>Du ble sendt hit fordi noen andre logget inn p&aring; brukeren p&aring; en annen ip.<br><span style="color:#c00">Om du mener at det er noen andre som har f&aring;tt tak i ditt passord og logger inn hele tiden, resett ditt passord igjennom "glemt passord" s&aring; snart som overhodet mulig! Om de har f&aring;tt kontroll over din email, ta kontakt p&aring; f&oslash;lgende emailadresse(du m&aring; kanskje opprette ny midlertidig email): <a href="mailto:baretester@live.no">baretester@live.no</a></span><br></p>';
    } else if ($g == 8) {
        $_SESSION['grunn'] = '<p>Crossing er ikke tillatt, utlogget!</p>';
    }
} else {
    $_SESSION['grunn'] = '<p>Du har n&aring; logget deg ut! Velkommen igjen!</p>';
}
header("Location: https://".DOMENE_NAVN);
