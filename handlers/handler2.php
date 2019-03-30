<?php
define("BASEPATH", true);
include('../system/config.php');
include('../classes/class.php');
header('Content-type: application/json'); /* Output til nettleser */

function codegen($length = 12)
{//Kodegenerator
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string     = null;
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
$str = ['string' => NULL, 'state' => 0, 'act' => 0];
if (isset($_GET['log'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (strlen($_POST['username']) === 0 || strlen($_POST['password']) === 0) {
            die(json_encode(['string' => "Ingen informasjon ble postet!", "state" => 0]));
        } else {
            $db = new database();
            $db->configure();
            $db->connect();
            $us = $db->escape($_POST['username']);
            $pa = md5(sha1($_POST['password']));
            $db->query("SELECT * FROM `users` WHERE `user` = '$us'");
            if ($db->num_rows() == 1) {
                $uid = $db->fetch_object();
                if ($uid->pass == $pa) {
                    if ($uid->health > 0 && $uid->moddet == 0) {
                        $str                    = ['string' => "<p class=\"lykket\">Innlogget! Et lite &oslash;yeblikk imens vi sender deg inn til nyheter...</p>",
                            'state' => 1, 'href' => "https://".DOMENE_NAVN."/nyheter.php"];
                        $_SESSION['sessionzar'] = array($uid->user, $uid->pass, safegen($uid->user, $uid->pass));
                        $ip                     = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'].$_SERVER['REMOTE_ADDR']
                                : $_SERVER['REMOTE_ADDR'];
                        $db->query("UPDATE `users` SET `lastactive` = '".time()."',`ip` = '$ip',`hostname`='".gethostbyaddr($ip)."' WHERE `id` = '{$uid->id}' AND `pass` = '{$uid->pass}'")or die("Feil".mysqli_error($db->connection_id));
                        /*$db->query("INSERT INTO `sessusr`(`uid`,`user_agent`,`ip`,`ip_host`,`security_key_gen`,`time`) VALUES('$uid->id','".$_SERVER['HTTP_USER_AGENT']."','".$_SERVER['REMOTE_ADDR']."','".gethostbyaddr($ip)."','".safegen($uid->user,
                                $uid->pass)."',UNIX_TIMESTAMP())");*/
                    } else {
                        if ($uid->moddet == 1) {
                            $str = array('string' => "<p class='feil'>$uid->user har blitt modkillet av {$uid->modav}.<br>Grunnlag: ".gen($uid->modgrunn)."</p>",
                                "state" => 0);
                        } elseif ($uid->health == 0) {
                            $str = array('string' => gen("<p class='feil'>Du har blitt drept! For &aring; spille, registrer en ny bruker!</p>"),
                                "state" => 0);
                        }
                    }
                } else {
                    //Feil passord
                    $str = array('string' => "<p class='feil'>Brukernavnet eller passordet er ikke rett, pr&oslash;v igjen!</p>",
                        "state" => 0);
                }
            } else {
                //Brukernavnet eksisterer ikke
                $str = array('string' => '<p class="feil">Brukernavnet finnes ikke!</p>', "state" => 0);
            }
            //$str = array('string'=>"Alt er ok!");
            if (!$str) {
                $str = array('string' => "Ingen tr&aring;der satt!", "state" => 0);
            }
        }
    } else {
        $str = array('string' => "Ingen informasjon ble sendt!", "state" => 0);
    }
    $str = json_encode(gen($str));
    print $str;
}
if (isset($_GET['getaccess'])) {
    $db = new database;
    $db->configure();
    $db->connect();
    $m  = $db->escape($_POST['email']);
    if (!filter_var($m, FILTER_VALIDATE_EMAIL)) {
        $str = array('string' => "<p style=\"color:#f00;\">Email ikke godkjent! Pr&oslash;v igjen!</p>");
        $str = json_encode(gen($str));
        print($str);
    } else {
        $res = $db->query("SELECT * FROM `invsjekk` WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."' AND `used` = '0' AND `time` > '".time()."'")or die(mysqli_error());
        if ($db->num_rows() == 0) {
            $used       = ($db->query("SELECT * FROM `users` WHERE `mail` = '".$m."' AND (`health` = '0' OR `moddet` = '1')"));
            $subject    = 'Registrering';
            $randomseed = rand();
            /* $db->query("CREATE TABLE IF NOT EXISTS `invsjekk`(`id` INT NOT NULL AUTO_INCREMENT,`mail` VARCHAR(255) NOT NULL,`code` VARCHAR(255) NOT NULL,`ip` VARCHAR(100) NOT NULL,`time` BIGINT NOT NULL,`used` ENUM('0','1') DEFAULT '0', PRIMARY KEY(`id`))"); */
            $db->query("INSERT INTO `invsjekk`(`mail`,`time`,`ip`,`code`) VALUES('".$m."','".(time() + 600)."','".$_SERVER['REMOTE_ADDR']."','".$randomseed."')")or die(mysqli_error());
            // message
            $message    = gen('
      <html>
      <head>
      <title>Invitasjon til registrering</title>
      </head>
      <body>
      <h1>Klikk p&aring; linken under for &aring; g&aring; til registrering</h1>
      <p><a href="https://'.DOMENE_NAVN.'/registermail.php?code='.$randomseed.'&mail='.urlencode($m).'">https://'.DOMENE_NAVN.'/registermail.php?code='.$randomseed.'&mail='.$m.'</a></p>
      </body>
      </html>
      ');

            // To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0'."\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";

            // Additional headers
            $headers .= 'To: '.$m.' <'.$m.'>'."\r\n";
            $headers .= 'From: '.NAVN_DOMENE.' <system@'.MAIL_SENDER.'>'."\r\n";

            // Mail it
            if (mail($m, $subject, $message, $headers)) {
                print(json_encode(gen(array('string' => gen('<p style="color:#008500">Det ble sendt en email til '.$m.' fra system@'.MAIL_SENDER.'! Sjekk innboks(mulig s&oslash;ppelpost ogs&aring;). Hvis du bruker Outlook, s&aring; vil du ikke motta mail pga. begrensninger hos dem.</p>')))));
            } else {
                print(json_encode(gen(array('string' => gen('<p style="color:#850000">Kunne ikke sende mail! Kan v&aelig;re feilkonfigurasjon i script eller ikke ferdig modul.</p>')))));
            }
        } else {
            /* M&aring; vente f&oslash;r ny mail kan sendes */
            $f    = $db->fetch_object();
            $left = $f->time - time();
            print(json_encode(gen(array('string' => '<p>Du m&aring; vente med &aring; motta ny invitasjon, eller bruke den du har mottatt p&aring; '.$f->mail.'. Det gjenst&aring;r '.$left.' sekunder f&oslash;r du kan pr&oslash;ve igjen.</p>'))));
        }
    }
    /* GETACCESS END */
}
if (isset($_GET['brukerreg'])) {
    $db = new database;
    $db->configure();
    $db->connect();
    $u  = $db->escape($_POST['user']);
    $p  = $db->escape($_POST['pass']);
    $m  = $db->escape($_POST['mail']);
    $c  = $db->escape($_POST['code']);
    $v  = $db->escape($_POST['vervetav']);
    $db->query("SELECT * FROM `users` WHERE `user` = '".$u."'");
    if (!preg_match("/^[a-z]+[\w._ -]*$/i", $u) || (strlen($u) <= 3 || strlen($u) >= 21) || (strlen($p) <= 3)) {
        $str = array('string' => '<p class="color:#f00;">Brukernavn ikke godkjent! Sjekk at du oppfyller kriteriene:<br>Bokstaver fra a-z(sm&aring; eller store) Du kan ogs&aring; bruke _(underscore) og mellomrom. Det kan v&aelig;re mellom 4-20 tegn. Du m&aring; ogs&aring; passe p&aring; at passordet inneholder minst 4 tegn eller mer.</p>');
        if (!preg_match("/^[a-z]+[\w._-]*$/i", $u)) {
            $str['string'] .= '<p>Brukernavnet ble ikke godkjent!</p>';
        }
        if (strlen($u) <= 3 || strlen($u) >= 21) {
            $str['string'] .= '<p>Brukernavnet m&aring; v&aelig;re mellom 4-20 tegn! Du hadde '.strlen($u).' tegn!</p>';
        }
        if (strlen($p) <= 3) {
            $str['string'] .= '<p>Passordet var for kort, ha minst 4 tegn!</p>';
        }
    } else {
        if ($db->num_rows() == 0) {
            /* Fortsetter registrering */
            /* Passord ok */
            $s = $db->query("SELECT * FROM `invsjekk` WHERE `code` = '".$c."' AND `mail` = '".$m."' AND `used` = '0'");
            if ($db->num_rows() == 1) {
                /* Registrerer brukeren */
                $vervet = 0;
                if (strlen($v) >= 1) {
                    $r = $db->query("SELECT * FROM `users` WHERE `id` = '$v'");
                    if ($db->num_rows($r) == 1) {
                        $r      = $db->fetch_object();
                        $vervet = $r->id;
                    } else {
                        $error = 1;
                    }
                }
                if (isset($error) && $error === 1) {
                    $str = array('string' => '<p style="color:#f00;">Brukeren din angav i verving ble ikke godkjent. Pr&oslash;v igjen, eller la feltet st&aring; tomt.</p>');
                } else {
                    $db->query("INSERT INTO `users`(`user`,`pass`,`mail`,`regip`,`reghostname`,`regdato`,`lastactive`,`vervetav`) VALUES('".$u."',MD5('".sha1($p)."'),'".$m."','".$_SERVER['REMOTE_ADDR']."','".gethostbyaddr($_SERVER['REMOTE_ADDR'])."','".time()."','0','".$vervet."')");
                    if ($db->affected_rows() == 1) {
                        $str = array('string' => '<p style="color:#0f0;">Du har blitt registrert, du kan n&aring; logge inn! <a href="http://'.DOMENE_NAVN.'/">Trykk her for &aring; g&aring; til innlogging</a></p>');
                        $db->query("UPDATE `invsjekk` SET `used` = '1' WHERE `mail` = '$m' AND `code` = '$c'");
                    } else {
                        $str = array('string' => '<p style="color:#f00;">Brukeren kunne ikke bli lagt inn i databasen, pr&oslash;v igjen, ta gjerne kontakt med support: support@'.MAIL_SENDER.'!<br>Feilen er:<br>'.mysqli_error($db->connection_id).'</p>');
                    }
                }
            } else {
                $str = array('string' => '<p style="color:#f00;">Koden er ikke godkjent! Den kan v&aelig;re brukt allerede, eller mailen har ingen tilknytning til koden.</p>');
            }
        } else {
            $str = array('string' => '<p style="color:#f00;">Du m&aring; velge et annet brukernavn, da dette er i bruk.</p>');
        }
    }
    $str = json_encode(gen($str));
    print $str;
}
if (isset($_GET['gpw'])) {
    $user = $_POST['user'];
    $mail = $_POST['mail'];
    if (strlen($user) >= 4 && strlen($user) <= 20 && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $db = new database;
        $db->configure();
        $db->connect();
        $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($user)."' AND `mail` = '".$db->escape($mail)."' AND `moddet` = '0' AND `health` > '0' ORDER BY `id` DESC LIMIT 1");
        if ($db->num_rows() == 1) {
            $i       = $db->fetch_object();
            $to      = $i->mail;
            $head    = 'Nytt passord';
            $resgen  = rand(1000010, 9999999);
            $db->query("INSERT INTO `resetpasset`(`uid`,`resgen`,`timemade`) VALUES('".$i->id."','".$resgen."',UNIX_TIMESTAMP())");
            $message = '
      <html>
      <head>
      <title>Nytt passord</title>
      </head>
      <body>
      <h1>Gjenopprett din brukerkonto</h1>
      <div style="width:95%;border-bottom:2px dotted #3e3e3e; margin: 0 auto;"></div>
      <p>Noen med f&oslash;lgende IP-adresse <i>'.$_SERVER['REMOTE_ADDR'].'</i> har bedt om at passordet p&aring; brukernavn <b>'.$i->user.'</b> skal tilbakestilles.<br>
      Klikk p&aring; denne lenken for &aring; tilbakestille passordet:<br>
      <a href="https://'.DOMENE_NAVN.'/resetpass.php?id='.$i->id.'&resgen='.$resgen.'">https://'.DOMENE_NAVN.'/resetpass.php?id='.$i->id.'&resgen='.$resgen.'</a><br>
      Om det ikke var du som ba om at passordet skulle tilbakestilles anbefales det at du ser bort fra denne mailen. Det kan ogs&aring; v&aelig;re det at noen har kontroll p&aring; din e-post og dermed pr&oslash;ver &aring; f&aring; tilgang til din bruker igjennom din e-post! Hvis du er smart, oppdater ditt passord p&aring; b&aring;de '.DOMENE_NAVN.' og hos din e-post-leverand&oslash;r!</p>
      </body>
      </html>
      ';
            $headers = 'MIME-Version: 1.0'."\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";

            // Additional headers
            $headers .= 'To: '.$i->user.' <'.$i->mail.'>'."\r\n";
            $headers .= 'From: '.MAIL_SENDER.' <'.HENVEND_MAIL.'>'."\r\n";
            if (mail($to, $head, $message, $headers)) {
                $str = array('string' => gen('<p style="color:#50a850">Det har blitt sendt en mail til mailadressen registrert p&aring; brukeren. Sjekk innboks/s&oslash;ppelpost.</p>'));
            } else {
                $str = array('string' => '<p style="color:#f00;">Mailen kunne ikke sendes, beklager. Ta kontakt med Ledelsen via mailadressen: <a href="mailto:system@'.MAIL_SENDER.'">system@'.MAIL_SENDER.'</a>.</p>');
            }
        } else {
            $str = array('string' => '<p style="color:#f00;">Det ble ikke funnet noen brukere med oppgitt informasjon, sjekk at du har skrevet riktig!</p>');
        }
    } else {
        $str = array('string' => gen('<p style="color:#f00">Det ble ikke oppgitt noen informasjon, sjekk at du skrev noe i feltene!</p>'));
    }
    print(json_encode(gen($str)));
    /* Glemt passord END */
}
if (isset($_GET['respas'])) {
    #die(print(json_encode(array('string'=>gen('<p style="color:#850000">Utilgjengelig for &oslash;yeblikket!</p>')))));
    /* Begynner tilbakestilling av passord */
    $p1  = $_POST['p1'];
    $p2  = $_POST['p2'];
    $uid = $_POST['uid'];
    $ip  = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'].' | '.$_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR'];
    if (strlen($p1) >= 4 && ($p1 == $p2) && is_numeric($uid)) {
        $db = new database();
        $db->configure();
        $db->connect();
        if ($db->connection_id) {
            /* Databasen er tilgjengelig, fortsetter sjekking og utf&oslash;relse */
            $s = $db->query("SELECT * FROM `resetpasset` WHERE `uid` = '".$db->escape($uid)."' AND `used` = '0' AND `rimming` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
            if ($db->num_rows() == 1) {
                /* Passord-endreren er fortsatt tilgjengelig */
                $f = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($uid)."' LIMIT 1");
                if ($db->num_rows() == 1) {
                    $obj = $db->fetch_object();
                    /* Brukeren eksisterer, fortsetter */
                    $db->query("UPDATE `users` SET `pass` = '".md5(sha1($p1))."' WHERE `id` = '".$db->escape($uid)."' LIMIT 1");
                    if ($db->affected_rows() == 1) {
                        /* Brukeren ble oppdatert */
                        $str = array('string' => gen('<p style="color:#50a850;">Ditt passord har blitt endret! Du kan n&aring; logge inn p&aring; innloggingssiden med det nye passordet ditt!</p>'),
                            'res' => 1);
                        /* Legger inn i passordendringslogg(why not have logs everywhere, right?) */
                        $db->query("INSERT INTO `respaslogg`(`uid`,`time`,`oldpass`,`newpass`,`ip`) VALUES('".$db->escape($uid)."',UNIX_TIMESTAMP(),'{$obj->pass}','".md5($p1)."','$ip')");
                        $db->query("UPDATE `resetpasset` SET `used` = '1' WHERE `uid` = '".$db->escape($uid)."' AND `used` = '0' ORDER BY `id` DESC LIMIT 1");
                        if ($db->affected_rows() == 1) {
                            $str["ls"] = 1;
                        } else {
                            $str["ls"] = 0;
                        }
                    } else if ($db->affected_rows() == 0) {
                        /* Kunne ikke oppdatere db av en eller annen grunn */
                        $str = array('string' => gen('<p style="color:#f00;">Kunne ikke oppdatere passordet! 2 muligheter st&aring;r:<br>Du pr&oslash;vde &aring; bruke samme passordet<br>Det var en feil i query til databasen! <br>Send en mail til baretester@live.no om problemet redvarer!</p>'),
                            'res' => 0);
                    }
                } else {
                    /* Brukeren eksisterer ikke, varsler "person" */
                    $str = array('string' => gen('<p style="color:#f00;">Brukerid-en finnes ikke!</p>'), 'res' => 0);
                }
            } else {
                /* Timestamp/used forteller at den ikke lengre er tilgjengelig */
                $str = array('string' => gen('<p class="color:#f00;">Denne koden er ikke lengre tilgjengelig!</p>'), 'res' => 0);
            }
        } else {
            /* Databasen er ikke tilgjengelig */
            $str = array('string' => gen('<p style="color:#f00;">Databasen er ikke tilgjengelig! Pr&oslash;v igjen senere!</p>'),
                'res' => 0);
        }
    } else {
        /* Validering slo ut p&aring; false p&aring; en eller flere valideringer */
        $str = array('string' => gen('<p style="color:#f00">Passordet ditt m&aring; v&aelig;re 4 tegn eller lengre, og v&aelig;re like i begge feltene under! Det kan ogs&aring; v&aelig;re at ikke riktig uid ble postet.</p>'),
            'res' => 0);
    }
    print(json_encode($str));
}