<?php
define("BASEPATH", true);
include '../system/config.php';
include '../classes/Database.php';
include '../inc/functions.php';
header('Content-type: application/json');

$str = ['string' => feil('Error: Not overwritten!'), 'state' => 0, 'act' => 0];
$ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']
    : $_SERVER['REMOTE_ADDR'];
$host = $_SERVER['SERVER_NAME'];
if ($host != "mafia.werzaire.net") {
    $domain = "localhost";
} else {
    $domain = DOMENE_NAVN;
}
if (isset($_GET['login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (strlen($_POST['username']) === 0 || strlen($_POST['password']) === 0) {
            $str['string'] = feil('Ingen informasjon ble postet!');
        } else {
            $db = new DatabaseObject\database();
            if ($db->connect()) {
                $us = $db->escape($_POST['username']);
                $pa = $_POST['password'];
                $db->query("SELECT * FROM `users` WHERE `user` = '$us'");
                if ($db->num_rows() == 1) {
                    $uid = $db->fetch_object();
                    if (password_verify($pa, $uid->pass)) {
                        if ($uid->health > 0) {
                            $str = [
                                'string' => lykket('Innlogget! Et lite &oslash;yeblikk imens vi sender deg inn 
                        til nyhetssiden...'),
                                'state' => 1,
                                'href' => 'https://' . $domain . '/nyheter.php'
                            ];
                            $_SESSION['sessionzar'] = [$uid->user, $uid->pass, safegen($uid->user, $uid->pass)];
                            $db->query("insert into sessions(id, uid, user_agent, user_ip, timestamp) VALUES (NULL, '{$uid->id}', '{$_SERVER["HTTP_USER_AGENT"]}','" . ip2long($ip) . "',UNIX_TIMESTAMP())");
                            $db->query("UPDATE `users` SET `lastactive` = UNIX_TIMESTAMP(),`ip` = '$ip',
                        `hostname`='" . gethostbyaddr($ip) . "' 
                        WHERE `id` = '{$uid->id}' AND `pass` = '{$uid->pass}'");
                        } else {
                            $str['string'] = feil('Du har blitt drept! For &aring; spille, registrer en ny bruker!');
                        }
                    } else {
                        $str['string'] = feil('Passordet stemte ikke overens med det vi har.');
                    }
                } else {
                    $str['string'] = feil('Brukernavnet finnes ikke!');
                }
                if (!$str) {
                    $str['string'] = info('Ingen tr&aring;der satt!');
                }
            } else {
                $str['string'] = feil('Kunne ikke koble til databasen. Sjekk error-loggen...');
            }
        }
    } else {
        $str['string'] = info('Ingen informasjon ble sendt!');
    }
}
if (isset($_GET['getaccess'])) {
    $db = new DatabaseObject\database();
    $db->connect();
    $m = $db->escape($_POST['email']);
    if (!filter_var($m, FILTER_VALIDATE_EMAIL)) {
        $str['string'] = feil('E-postadressen ikke godkjent! Pr&oslash;v igjen!');
    } else {
        $res = $db->query("SELECT * FROM `invsjekk` WHERE `ip` = '" . $_SERVER['REMOTE_ADDR'] . "' 
        AND `used` = '0' AND `timestamp` > '" . time() . "'");
        if ($db->num_rows() == 0) {
            $used = ($db->query("SELECT * FROM `users` WHERE `mail` = '$m' AND `health` = '0' LIMIT 1"));
            $subject = 'Registrering';
            $randomseed = rand();
            $db->query("INSERT INTO `invsjekk`(`mail`,timestamp,`ip`,`code`) 
VALUES('" . $m . "','" . (time() + 600) . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $randomseed . "')");
            $url = 'https://' . DOMENE_NAVN . '/registermail.php?code=' . $randomseed . '&mail=' . urlencode($m);
            $message = '
      <html>
      <head>
      <title>Invitasjon til registrering</title>
      </head>
      <body>
      <h1>Klikk p&aring; linken under for &aring; g&aring; til registrering</h1>
      <p><a href="' . $url . '">' . $url . '</a></p>
      </body>
      </html>
      ';
            $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'To: ' . $m . ' <' . $m . '>' . "\r\n";
            $headers .= 'From: ' . NAVN_DOMENE . ' <noreply@' . MAIL_SENDER . '>' . "\r\n";
            if (mail($m, $subject, $message, $headers)) {
                $str['string'] = lykket('Det ble sendt en email til ' . htmlentities($m) . ' 
                fra noreply@' . MAIL_SENDER . '! 
                Sjekk innboks(mulig s&oslash;ppelpost ogs&aring;). 
                Hvis du bruker Outlook, s&aring; vil du ikke motta mail pga. begrensninger hos dem.');
                $str['res'] = 1;
            } else {
                $str['string'] = feil('Kunne ikke sende mail! Kan v&aelig;re feilkonfigurasjon 
                i script eller ikke ferdig modul.');
            }
        } else {
            $f = $db->fetch_object();
            $left = $f->time - time();
            $str['string'] = feil('Du m&aring; vente med &aring; motta ny invitasjon, 
            eller bruke den du har mottatt p&aring; ' . $f->mail . '. 
            Det gjenst&aring;r ' . $left . ' sekunder f&oslash;r du kan pr&oslash;ve igjen.');
        }
    }
}
if (isset($_GET['brukerreg'])) {
    $db = new DatabaseObject\database();
    $db->connect();
    $u = $db->escape($_POST['user']);
    $p = $_POST['pass'];
    $m = $db->escape($_POST['mail']);
    $c = $db->escape($_POST['code']);
    $v = $db->escape($_POST['vervetav']);
    $db->query("SELECT * FROM `users` WHERE `user` = '$u'");
    if (!preg_match("/^[a-z]+[\w._ -]*$/i", $u) || (strlen($u) <= 3 || strlen($u) >= 21) || (strlen($p) <= 3)) {
        $str['string'] = feil('Brukernavn ikke godkjent! Sjekk at du oppfyller kriteriene:<br>
Bokstaver fra a-z(sm&aring; eller store) Du kan ogs&aring; bruke _(underscore) og mellomrom. 
Det kan v&aelig;re mellom 4-20 tegn. 
Du m&aring; ogs&aring; passe p&aring; at passordet inneholder minst 4 tegn eller mer.');
        if (!preg_match("/^[a-z]+[\w._-]*$/i", $u)) {
            $str['string'] .= '<p>Brukernavnet ble ikke godkjent!</p>';
        }
        if (strlen($u) <= 3 || strlen($u) >= 21) {
            $str['string'] .= '<p>Brukernavnet m&aring; v&aelig;re mellom 4-20 tegn! Du hadde 
' . strlen($u) . ' tegn!</p>';
        }
        if (strlen($p) <= 3) {
            $str['string'] .= '<p>Passordet var for kort, ha minst 4 tegn!</p>';
        }
    } else {
        if ($db->num_rows() == 0) {
            $s = $db->query("SELECT * FROM `invsjekk` WHERE `code` = '$c' AND `mail` = '$m' AND `used` = '0'");
            if ($db->num_rows() == 1) {
                $vervet = 0;
                if (strlen($v) >= 1) {
                    $r = $db->query("SELECT * FROM `users` WHERE `id` = '$v'");
                    if ($db->num_rows($r) == 1) {
                        $r = $db->fetch_object();
                        $vervet = $r->id;
                    } else {
                        $error = 1;
                    }
                }
                if (isset($error) && $error === 1) {
                    $str['string'] = feil('Brukeren din angav i verving ble ikke godkjent. 
                    Pr&oslash;v igjen, eller la feltet st&aring; tomt.');
                } else {
                    $password = password_hash($p, PASSWORD_BCRYPT);
                    $db->query("INSERT INTO `users`(`user`,`pass`,`mail`,`regip`,`reghostname`,
                    regstamp,`lastactive`) 
                    VALUES('$u','$password','$m','$ip','" . gethostbyaddr($ip) . "',UNIX_TIMESTAMP(),'0')");
                    if ($db->affected_rows() == 1) {
                        $str['string'] = lykket('Du har blitt registrert, du kan n&aring; logge inn! 
                        <a href="http://' . DOMENE_NAVN . '/">Trykk her for &aring; g&aring; til innlogging</a>');
                        $str['res'] = 1;
                        $db->query("UPDATE `invsjekk` SET `used` = '1' WHERE `mail` = '$m' AND `code` = '$c'");
                    } else {
                        $str['string'] = feil('Brukeren kunne ikke bli lagt inn i databasen, pr&oslash;v igjen, 
                        ta gjerne kontakt med support: support@' . MAIL_SENDER . '!');
                    }
                }
            } else {
                $str['string'] = feil('Koden er ikke godkjent! Den kan v&aelig;re brukt allerede, 
                eller mailen har ingen tilknytning til koden.');
            }
        } else {
            $str['string'] = feil('Du m&aring; velge et annet brukernavn, da dette er i bruk.');
        }
    }
}
if (isset($_GET['forgotpassword'])) {
    $user = $_POST['user'];
    $mail = $_POST['mail'];
    if (strlen($user) >= 4 && strlen($user) <= 20 && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $db = new DatabaseObject\database();
        $db->connect();
        $user = $db->escape($user);
        $db->query("SELECT * FROM `users` WHERE `user` = '$user' AND `mail` = '$mail' AND `health` > '0'");
        if ($db->num_rows() == 1) {
            $i = $db->fetch_object();
            $to = $i->mail;
            $head = 'Nytt passord';
            $resgen = rand(1000010, 9999999);
            $db->query("INSERT INTO `resetpasset`(`uid`,`resgen`,`timestamp`)
 VALUES('{$i->id}','$resgen',UNIX_TIMESTAMP())");
            $message = '
      <html>
      <head>
      <title>Nytt passord</title>
      </head>
      <body>
      <h1>Gjenopprett din brukerkonto</h1>
      <div style="width:95%;border-bottom:2px dotted #3e3e3e; margin: 0 auto;"></div>
      <p>Noen med f&oslash;lgende IP-adresse <i>' . $_SERVER['REMOTE_ADDR'] . '</i> har bedt om at passordet p&aring;
       brukernavn <b>' . $i->user . '</b> skal tilbakestilles.<br>
      Klikk p&aring; denne lenken for &aring; tilbakestille passordet:<br>
      <a href="https://' . DOMENE_NAVN . '/resetpass.php?id=' . $i->id . '&resgen=' . $resgen . '">
      https://' . DOMENE_NAVN . '/resetpass.php?id=' . $i->id . '&resgen=' . $resgen . '</a><br>
      Om det ikke var du som ba om at passordet skulle tilbakestilles anbefales det at du ser bort fra denne mailen.
       Det kan ogs&aring; v&aelig;re det at noen har kontroll p&aring; din e-post og dermed pr&oslash;ver &aring; 
       f&aring; tilgang til din bruker igjennom din e-post! Hvis du er smart, oppdater ditt passord p&aring; b&aring;de
        ' . DOMENE_NAVN . ' og hos din e-post-leverand&oslash;r!</p>
      </body>
      </html>
      ';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

            // Additional headers
            $headers .= 'To: ' . $i->user . ' <' . $i->mail . '>' . "\r\n";
            $headers .= 'From: ' . MAIL_SENDER . ' <' . HENVEND_MAIL . '>' . "\r\n";
            if (mail($to, $head, $message, $headers)) {
                $str['string'] = lykket('Det har blitt sendt en mail til mailadressen
 registrert p&aring; brukeren. Sjekk innboks/s&oslash;ppelpost.');
            } else {
                $str['string'] = feil('Mailen kunne ikke sendes, beklager. Ta kontakt med 
Ledelsen via mailadressen: <a href="mailto:system@' . MAIL_SENDER . '">system@' . MAIL_SENDER . '</a>.');
            }
        } else {
            $str['string'] = feil('Det ble ikke funnet noen brukere med oppgitt informasjon, 
sjekk at du har skrevet riktig!');
        }
    } else {
        $str['string'] = feil('Det ble ikke oppgitt noen informasjon, sjekk at du skrev noe i feltene!');
    }
}
if (isset($_GET['resetpassword'])) {
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];
    $uid = $_POST['uid'];
    $ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ?
        $_SERVER['HTTP_X_FORWARDED_FOR'] . ' | ' . $_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR'];
    if (strlen($p1) >= 4 && ($p1 == $p2) && is_numeric($uid)) {
        $db = new DatabaseObject\database();
        $db->connect();
        $uid = $db->escape($_POST['uid']);
        $pass = password_hash($p1, PASSWORD_BCRYPT);
        if ($db->con) {
            $s = $db->query("SELECT * FROM `resetpasset` WHERE `uid` = '$uid' AND `used` = '0'
                              AND (`timestamp` + 3600) > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
            if ($db->num_rows() == 1) {
                $f = $db->query("SELECT * FROM `users` WHERE `id` = '" . $db->escape($uid) . "' LIMIT 1");
                if ($db->num_rows() == 1) {
                    $obj = $db->fetch_object();
                    $db->query("UPDATE `users` SET `pass` = '$pass' WHERE
                     `id` = '$uid' LIMIT 1");
                    if ($db->affected_rows() == 1) {
                        $str['string'] = lykket('Ditt passord har blitt endret! <br>
Du kan n&aring; logge inn p&aring; innloggingssiden med det nye passordet ditt!');
                        $str['res'] = 1;
                        /*$db->query("INSERT INTO `respaslogg`(`uid`,`time`,`oldpass`,`newpass`,`ip`)
VALUES('$uid',UNIX_TIMESTAMP(),'{$obj->pass}','" . md5($p1) . "','$ip')"); ** Might reimplement later*/
                        $db->query("UPDATE `resetpasset` SET `used` = '1' 
WHERE `uid` = '" . $db->escape($uid) . "' AND `used` = '0' ORDER BY `id` DESC LIMIT 1");
                        if ($db->affected_rows() == 1) {
                            $str['string'] = lykket('Passordet er oppdatert! G&aring; til innlogging for &aring; fortsette!');
                        } else {
                            $str['string'] = feil('Kunne ikke oppdatere passordet, kontakt admin!');
                        }
                    } else {
                        if ($db->affected_rows() == 0) {
                            $str['string'] = feil('Kunne ikke oppdatere passordet! 
2 muligheter st&aring;r:<br>Du pr&oslash;vde &aring; bruke samme passordet<br>Det var en feil i query til databasen! 
<br>Send en mail til ' . HENVEND_MAIL . ' om problemet redvarer!');
                        }
                    }
                } else {
                    $str['string'] = feil('Brukerid-en finnes ikke!');
                }
            } else {
                $str['string'] = feil('Denne koden er ikke lengre tilgjengelig!');
            }
        } else {
            $str['string'] = feil('Databasen er ikke tilgjengelig! Pr&oslash;v igjen senere!');
        }
    } else {
        $str['string'] = feil('Passordet ditt m&aring; v&aelig;re 4 tegn eller lengre, og v&aelig;re like i begge feltene under! Det kan ogs&aring; v&aelig;re at ikke riktig uid ble postet.');
    }
}
print(json_encode($str));
