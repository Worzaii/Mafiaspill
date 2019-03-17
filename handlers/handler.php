<?php
sleep(1);
header('Content-Type: text/html; charset=iso-8859-1');
define("BASEPATH", true);
include("../system/config.php");
session_start();
if (isset($_GET['login']) || isset($_GET['reg']) || isset($_GET['pass'])) {
    include("classes/class.php");
    $db = new database;
    if (isset($_GET['login'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $db->configure();
            $db->connect();
            $u = $db->escape($_POST['username']);
            $p = md5($_POST['password']);
            if (strlen($u) <= 1 || strlen($p) <= 1) {
                echo '<p class="feil">Du må fylle ut alle feltene.</p>';
            } else {
                $db->query("SELECT * FROM `users` WHERE `user` = '$u' AND `pass` = '$p' AND `activated` = '1' AND `status` <> '5'");
                if ($db->num_rows() == 1) {
                    $g                      = $db->fetch_object();
                    $_SESSION['sessionzar'] = array($g->user, $g->pass);
                    $time                   = time();
                    $ip                     = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR']
                            : $_SERVER['REMOTE_ADDR'];
                    $db->query("UPDATE `users` SET `lastactive` = '$time',`ip` = '$ip' WHERE `id` = '$g->id' AND `pass` = '$p'");
                    echo <<<END
<p class="lykket">Du har blitt logget inn, sender deg videre!<br />Om siden ikke lastet inn på nytt, klikk her: <a href="/Nyheter">Nyheter</a>.</p>
<script type="text/javascript">
var eventy = '<scr'+'ipt type="text/javascript">window.location.href="http://mafia-no.net/Nyheter";</scr' + 'ipt>';
eval(eventy);
</script>
END;
                } else {
                    $db->query("SELECT * FROM `users` WHERE `user` = '$u' AND `pass` = '$p' AND `activated` = '0'");
                    if ($db->num_rows() == 1) {
                        echo '<p class="lykket">Din bruker har ikke blitt aktivert enda, aktiver brukeren igjennom emailen du mottok på mail. Om du har skrevet feil når du registrerte deg, ta kontakt med oss! <a href="mailto:system@mafia-no.net">system@mafia-no.net</a>.</p>';
                    } else {
                        echo '
                        <p class="feil">Brukernavnet og passordet stemte ikke, prøv igjen. Det kan også være at din bruker er død.</p>
                        ';
                    }
                }
            }
        } else {
            echo '<p>Du må fylle ut alle feltene,hmm!</p>';
            exit;
        }
    } else if (isset($_GET['reg'])) {
        #echo '<p>Registrering er ikke klar!</p>';

        function randn1($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $string     = null;
            for ($p = 0; $p < $length; $p++) {
                $string .= $characters[mt_rand(0, strlen($characters))];
            }
            return $string;
        }
        if (isset($_GET['code'])) {
            $code = mysql_real_escape_string($_GET['code']);
            if (strlen($code) < 9 || strlen($code) >= 12) {//9-11
                echo '<p>Du kan ikke aktivere brukeren med denne koden. Den er for kort eller for lang.';
            } else {
                $r = $db->query("SELECT * FROM `regcodes` WHERE `code` = '$code' ORDER BY `id` DESC LIMIT 1");
                if ($db->num_rows() >= 1) {
                    $rr = $db->fetch_object($r);
                    if ($rr->code == $code) {
                        if ($db->query("UPDATE `regcodes` SET `used` = '1' WHERE `code` = '$code'")) {
                            if (mysql_query("UPDATE `users` SET `activated` = '1' WHERE `id` = '$rr->uid'")) {
                                echo '<p style="color:#0f0;font-size:14px;font-weight:bold;">Din bruker har blitt aktivert! Du kan nå logge inn.</p>';
                            } else {
                                echo '<p>Kunne ikke oppdatere din bruker... Du kan rapportere dette til Ledelsen via mail: <a href="mailto:system@mafia-no.net">system@mafia-no.net</a></p>';
                            }
                        } else {
                            echo '<p>Av en grunn vil ikke linken oppdateres i db, dermed vil ikke din bruker heller oppdateres. Prøv igjen senere!</p>';
                        }
                    } else {
                        echo '<p>Koden stemte ikke overens med det registrerte.</p>';
                    }
                } else {
                    echo '<p>Denne koden er ikke gyldig. Vennligst sjekk at du brukte riktig link. Har du problemer med aktivering, ta kontakt med <a href="mailto:system@mafia-no.net">system@mafia-no.net</a></p>';
                }
            }
        }
        //ActiEND
        if (isset($_POST['user'])) {
            if (!isset($_POST['email']) || !isset($_POST['pass']) || !isset($_POST['vpass']) || !isset($_POST['captcha_code'])) {
                echo "<p>Du må fylle inn alle feltene.</p>";
            }
            $db->configure();
            $db->connect();
            include_once("CaptCha/securimage.php");
            $image   = new Securimage();
            $user    = $db->escape($_POST['user']);
            $mail    = $db->escape($_POST['email']);
            $pas1    = $db->escape($_POST['pass']);
            $pas2    = $db->escape($_POST['vpass']);
            $ip      = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $sqlrad  = $db->query("SELECT * FROM `users` WHERE `user` = '$user'")or die(mysql_error());
            $sqlrad2 = $db->query("SELECT * FROM `users` WHERE `mail` = '$mail' AND `activated` = '1'")or die(mysql_error());
            $rad     = $db->num_rows($sqlrad);
            $rad2    = $db->num_rows($sqlrad2);
            $res     = null;
            if ($rad >= 1 || $rad2 >= 1 || strlen($user) <= 3 || strlen($user) >= 16 || !preg_match("/^[a-zA-Z]+[\w-_]*$/i",
                    $user) || !filter_var($mail, FILTER_VALIDATE_EMAIL) || $pas1 != $pas2 || $image->check($_POST['captcha_code'])
                == false) {
                $res .= '
                <p class="feil">Flere feil funnet ved registrering!</p>
                ';
                if ($rad >= 1) {
                    $res .= '<p class="feil">Brukernavnet er allerede i bruk.</p>';
                }
                if ($rad2 >= 1) {
                    $res .= '<p class="feil">E-posten er allerede i bruk. Vennligst bruk en som er din.</p>';
                }
                if (strlen($user) <= 3) {
                    $res .= '<p class="feil">Brukernavnet du har valgt deg er for kort! Det må være 4 tegn eller lengre. Max 15 tegn er tillatt for brukernavn.</p>';
                }
                if (strlen($user) >= 16) {
                    $res .= '<p class="feil">Brukernavnet du har valgt deg er for langt! Det må være 15 tegn eller mindre. Max 15 tegn, minimum 4 tegn, er tillatt for brukernavn.</p>';
                }
                if (!preg_match("/^[a-zA-Z]+[\w-_]*$/i", $user)) {
                    $res .= '<p class="feil">Brukernavnet du valgte er ikke gyldig! Du kan bruke kun disse tegnene: a-Z + 0-9 og _ og - Andre tegn vil ikke tillates.</p>';
                }
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $res .= '<p class="feil">Den emailen du oppgav er ikke gyldig! Vennligst oppgi en gyldig E-mail. Uten din email får du ikke aktivert brukeren.</p>';
                }
                if ($pas1 != $pas2) {
                    $res .= '<p class="feil">Passordene du oppgav var ikke like. Pass på at du har et passord du husker godt og som samtidig ikke er så vanskelig å gjette seg til.</p>';
                }
                if ($image->check($_POST['captcha_code']) == false) {
                    $res .= '<p class="feil">Du klarte ikke Antibotten!</p>';
                }
                echo $res;
            } else {
                $pass = md5($pas1);
                $date = date("H:i:s d.m.y");
                if ($db->query("INSERT INTO `users`(`user`,`pass`,`mail`,`ip`,`lastdato`,`regdato`) VALUES('$user','$pass','$mail','$ip','0','$date')")) {
                    $code    = randn1();
                    $tittel  = 'Registrering - Aktiveringslink';
                    $melding = '
                    <html>
                    <head>
                    <title>Registrasjon/Aktiveringslink</title>
                    </head>
                    <body>
                    <h1>Takk for at du, '.$user.', registrerte deg hos oss!</h1>
                    <hr />
                    <p class="feil">For å aktivere din bruker, klikk på linken under:<br /><a href="http://mafia-no.net/registrerdeg.php?code='.$code.'">Aktiver bruker!</a></p>
                    <p class="feil">Noen mail-klienter kan ikke klikke på slike linker. I det tilfellet, så må du kopiere denne url-en og lime den inn i nettleseren din:<br />
                    http://mafia-no.net/registrerdeg.php?code='.$code.'</p>
                    <br /><br />
                    <p class="feil"><b>Hilsen,</b><br />Mafia-no.net sin ledelse.</p>
                    </body>
                    </html>
                    ';
                    $headers = 'MIME-Version: 1.0'."\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                    $headers .= 'To: '.$user.' <'.$mail.'>'."\r\n";
                    $headers .= 'From: Mafia-no.net <system@mafia-no.net>'."\r\n";
                    if (!mail($mail, $tittel, $melding, $headers)) {
                        echo '<p style="color:#f00;font-size:20px;font-weight:bold;">Mailen kunne ikke sendes! Ta kontakt med Ledelsen, så du får mottat mailen med aktiveringslinken.</p>';
                    }
                    $usid = mysql_query("SELECT * FROM `users` WHERE `user` = '$user'");
                    $uid  = mysql_fetch_object($usid);
                    if ($db->query("INSERT INTO `regcodes`(`uid`,`code`,`date`) VALUES('$uid->id','$code','$date')")) {
                        $res .= '
                        <p style="color:#0f0;font-family:\'Comic Sans MS\',\'Times New Roman\'">Du har blitt registrert! Du skal nå ha mottat en mail med aktiveringslink. Sjekk din søppelpost om du ikke finner mailen med aktiveringslink.Om du ikke mottar eposten innen 5 minutter, så kan det være at vår mailserver ikke klarte å sende mailen til deg, da kan du skrive en e-mail til <a href="mailto:system@mafia-no.net">system@mafia-no.net</a> med ditt brukernavn og mailen du registrerte med så skal vi prøve å hjelpe deg.</p>
                        ';
                    } else {
                        $res .= '<p style="font-family:\'Comic Sans MS\',\'Times New Roman\'">Du har blitt registrert, men det oppstod en feil med aktiveringskoden. Send mail til <a href="mailto:system@mafia-no.net">system@mafia-no.net</a> og varsle om dette. Evt. skriv detaljer om ditt brukernavn, mail og lignende.</p>';
                    }
                } else {
                    $res         .= '
                    <p>Din bruker kunne ikke registreres!</p>
                    ';
                    $filename    = '/logger/reglogg.txt';
                    $somecontent = $db->query_error();
                    if (is_writable($filename)) {
                        if (!$handle = fopen($filename, 'a')) {
                            echo "Cannot open file ($filename)";
                            exit;
                        }
                        if (fwrite($handle, $somecontent) === FALSE) {
                            echo "Kan ikke skrive til fil: ($filename)";
                            exit;
                        }
                        fclose($handle);
                    } else {
                        echo "<p>Filen $filename er ikke skrivbar!</p>";
                    }
                }
            }
        } else {
            echo '<p>Du må skrive inn et brukernavn!</p>';
        }
        echo $res;
    }//Registrering END
    else if (isset($_GET['pass'])) {
        echo '<p>Glemt passord er ikke klart!</p>';
    }//Glemt pass END
} else {
    header('Location: /');
    echo '<p>Siden du prøver å nå finnes ikke!</p>';
}
//echo '<p>Dette scriptet fungerer som det skal!</p>';
//echo '<p>Headers sent:<br />Post:'.print_r($_POST,true).'<br />GET:'.print_r($_GET,true).'</p>';
?>
