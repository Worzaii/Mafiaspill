<?php

define("BASEPATH", true);
include '../system/config.php';
include '../inc/functions.php';
header('Content-type: application/json');

$str = ['string' => feil('Error: Not overwritten!'), 'state' => 0, 'act' => 0];
$ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']
    : $_SERVER['REMOTE_ADDR'];
$domain = $_SERVER['SERVER_NAME'];
if (!(isset($_GET['login']) || isset($_GET['getaccess']) || isset($_GET['brukerreg']) || isset($_GET['forgotpassword']) || isset($_GET['resetpassword']))) {
    die(json_encode($str)); /* Saving some time, hopefully */
}
include_once '../inc/pdoinc.php';
if (isset($_GET['login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (strlen($_POST['username']) === 0 || strlen($_POST['password']) === 0) {
            $str['string'] = feil('Ingen informasjon ble postet!');
        } else {
            $us = $_POST['username'];
            $pa = $_POST['password'];
            $st = $db->prepare("SELECT count(*) FROM `users` WHERE `user` = ?");
            $st->execute([$us]);
            if ($st->fetchColumn() === 1) {
                $st2 = $db->prepare("SELECT id, pass, user, health FROM `users` WHERE `user` = ?");
                $st2->execute([$us]);
                $uid = $st2->fetchObject();
                if (password_verify($pa, $uid->pass) && $uid->health > 0) {
                    $_SESSION['sessionzar'] = [
                        $uid->user,
                        $uid->pass,
                        safegen($uid->user, $uid->pass)
                    ];
                    $st3 = $db->prepare(
                        "insert into sessions(uid, user_agent, user_ip, timestamp) VALUES (?, ?, ? ,UNIX_TIMESTAMP())"
                    );
                    $st3->execute([$uid->id, $_SERVER["HTTP_USER_AGENT"], ip2long($ip)]);

                    $st4 = $db->prepare(
                        "UPDATE `users` SET `lastactive` = UNIX_TIMESTAMP(), `ip` = ?, `hostname` = ? where `id` = ? AND `pass` = ?"
                    );
                    $st4->execute([$ip, gethostbyaddr($ip), $uid->id, $uid->pass]);

                    $str = [
                        'string' => lykket('Innlogget! Et lite øyeblikk imens vi sender deg inn til nyhetssiden...'),
                        'state' => 1,
                        'href' => 'https://' . $domain . '/nyheter.php'
                    ];
                } else {
                    $str['string'] = feil('Feil passord');
                }
            } else {
                $str['string'] = feil('Konto eksisterer ikke');
            }
        }
    } else {
        $str['string'] = info('Ingen informasjon ble sendt!');
    }
}
if (isset($_GET['getaccess'])) {
    include_once '../inc/pdoinc.php';
    $m = $_POST['email'];
    if (!filter_var($m, FILTER_VALIDATE_EMAIL)) {
        $str['string'] = feil('E-postadressen ikke godkjent! Prøv igjen!');
    } else {
        $res = $db->query(
            "SELECT COUNT(*) as numrows FROM `invsjekk` WHERE `ip` = '" . $_SERVER['REMOTE_ADDR'] . "'
        AND `used` = '0' AND `timestamp` > '" . time() . "'"
        );
        if ($res->fetchColumn() == 0) {
            //$used = $db->query("SELECT * FROM `users` WHERE `mail` = '$m' AND `health` = '0' LIMIT 1"); /* Apparely not in use? */
            $subject = 'Registrering';
            $randomseed = rand();
            $ins = $db->prepare(
                "INSERT INTO `invsjekk`(`mail`,timestamp,`ip`,`code`)
VALUES(?,(UNIX_TIMESTAMP() + 600),?,?)"
            );
            if ($ins->execute([$m, $ip, $randomseed])) {
                $url = 'https://' . DOMENE_NAVN . '/registermail.php?code=' . $randomseed . '&mail=' . urlencode($m);
                $message = "
                    <html>
                    <head>
                    <title>Invitasjon til registrering</title>
                    </head>
                    <body>
                    <h1>Klikk på linken under for å gå til registrering</h1>
                    <p><a href=\"{$url}\">{$url}</a></p>
                    </body>
                    </html>
                    ";
                $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
                $headers .= 'To: ' . $m . ' <' . $m . '>' . "\r\n";
                $headers .= 'From: ' . NAVN_DOMENE . ' <noreply@' . MAIL_SENDER . '>' . "\r\n";
                if (mail($m, $subject, $message, $headers)) {
                    $str['string'] = lykket(
                        'Det ble sendt en email til ' . htmlentities($m) . '
                fra noreply@' . MAIL_SENDER . '!
                Sjekk innboks(mulig søppelpost også).'
                    );
                    $str['res'] = 1;
                } else {
                    $str['string'] = feil(
                        'Kunne ikke sende mail! Kan være feilkonfigurasjon
                i script eller ikke ferdig modul.'
                    );
                }
            }
        } else {
            $f = $db->fetch_object();
            $left = $f->time - time();
            $str['string'] = feil(
                'Du må vente med å motta ny invitasjon,
            eller bruke den du har mottatt på ' . $f->mail . '.
            Det gjenstår ' . $left . ' sekunder før du kan prøve igjen.'
            );
        }
    }
}
if (isset($_GET['brukerreg'])) {
    include_once '../inc/pdoinc.php';
    $u = $_POST['user'];
    $p = $_POST['pass'];
    $m = $_POST['mail'];
    $c = $_POST['code'];
    $v = $_POST['vervetav'];
    $check = $db->prepare("SELECT count(*) FROM `users` WHERE `user` = ?");
    $check->execute([$u]);
    if ($check->fetchColumn() != 1) {
        /* No users go by that name, continuing... */
        if (!preg_match(
                "/^[a-z]+[\w._-]*$/i",
                $u
            ) || (strlen($u) <= 3 || strlen($u) >= 21) || (strlen($p) <= 3)) {
            $str['string'] = feil(
                'Brukernavn ikke godkjent! Sjekk at du oppfyller kriteriene:<br>
Bokstaver fra a-z(små eller store) Du kan også bruke _(underscore).
Det kan være mellom 4-20 tegn.
Du må også passe på at passordet inneholder minst 4 tegn eller mer.'
            );
            if (!preg_match("/^[a-z]+[\w._-]*$/i", $u)) {
                $str['string'] .= '<p>Brukernavnet ble ikke godkjent!</p>';
            }
            if (strlen($u) <= 3 || strlen($u) >= 21) {
                $str['string'] .= '<p>Brukernavnet må være mellom 4-20 tegn! Du hadde
' . strlen($u) . ' tegn!</p>';
            }
            if (strlen($p) <= 3) {
                $str['string'] .= '<p>Passordet var for kort, ha minst 4 tegn!</p>';
            }
        } else {
            $s = $db->prepare("SELECT count(*) FROM `invsjekk` WHERE `code` = ? AND `mail` = ? AND `used` = '0'");
            $s->execute([$c, $m]);
            if ($s->fetchColumn() == 1) {
                $s2 = $db->prepare("SELECT * FROM `invsjekk` WHERE `code` = ? AND `mail` = ? AND `used` = '0'");
                $s2->execute([$c, $m]);
                $vervet = 0;
                if (strlen($v) >= 1) {
                    $r = $db->prepare("SELECT count(*) FROM `users` WHERE `id` = ?");
                    $r->execute([$v]);

                    if ($r->fetchColumn() == 1) {
                        $r = $db->prepare("SELECT id FROM `users` WHERE `id` = ?");
                        $r->execute([$v]);
                        $vervet = $r->fetchColumn();
                    } else {
                        $error = 1;
                    }
                }
                if (isset($error) && $error === 1) {
                    $str['string'] = feil(
                        'Brukeren din angav i verving ble ikke godkjent.
                    Prøv igjen, eller la feltet stå tomt.'
                    );
                } else {
                    $password = password_hash($p, PASSWORD_BCRYPT);
                    $newuser = $db->prepare(
                        "INSERT INTO `users`(`user`,`pass`,`mail`,`regip`,`reghostname`,
                    regstamp,`lastactive`)
                    VALUES(?,?,?,?,?,UNIX_TIMESTAMP(),'0')"
                    );
                    $newuser->execute(
                        [
                            $u,
                            $password,
                            $m,
                            $ip,
                            gethostbyaddr($ip)
                        ]
                    );
                    if ($newuser->rowCount() == 1) {
                        $str['string'] = lykket(
                            'Du har blitt registrert, du kan nå logge inn!
                        <a href="https://' . DOMENE_NAVN . '/">Trykk her for å gå til innlogging</a>'
                        );
                        $str['res'] = 1;
                        $inv = $db->prepare("UPDATE `invsjekk` SET `used` = '1' WHERE `mail` = ? AND `code` = ?");
                        $inv->execute(
                            [
                                $m,
                                $c
                            ]
                        );
                        if ($inv->rowCount() == 0) {
                            error_log(
                                "ERROR! Couldn't update invsjekk! Error?\n" . var_export(
                                    $inv->errorInfo(),
                                    true
                                )
                            );
                        }
                    } else {
                        $str['string'] = feil(
                            'Brukeren kunne ikke bli lagt inn i databasen, prøv igjen,
                        ta gjerne kontakt med support: support@' . MAIL_SENDER . '!'
                        );
                    }
                }
            } else {
                $str['string'] = feil(
                    'Koden er ikke godkjent! Den kan være brukt allerede,
                eller mailen har ingen tilknytning til koden.'
                );
            }
        }
    } else {
        $str['string'] = feil('Du må velge et annet brukernavn, da dette er i bruk.');
    }
}
if (isset($_GET['forgotpassword'])) {
    $user = $_POST['user'];
    $mail = $_POST['mail'];
    if (strlen($user) >= 4 && strlen($user) <= 20 && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        include_once '../inc/pdoinc.php';
        $fp = $db->prepare("SELECT count(*) FROM `users` WHERE `user` = ? AND `mail` = ? AND `health` > '0'");
        $fp->execute(
            [
                $user,
                $mail
            ]
        );
        if ($fp->fetchColumn() == 1) {
            $fp2 = $db->prepare("SELECT mail,user,id FROM `users` WHERE `user` = ? AND `mail` = ? AND `health` > '0'");
            $fp2->execute(
                [
                    $user,
                    $mail
                ]
            );
            $i = $fp2->fetchObject();
            $to = $i->mail;
            $head = 'Nytt passord';
            $resgen = rand(1000010, 9999999);
            $db->query(
                "INSERT INTO `resetpasset`(`uid`,`resgen`,`timestamp`)
 VALUES('{$i->id}','$resgen',UNIX_TIMESTAMP())"
            );
            $message = '
      <html>
      <head>
      <title>Nytt passord</title>
      </head>
      <body>
      <h1>Gjenopprett din brukerkonto</h1>
      <div style="width:95%;border-bottom:2px dotted #3e3e3e; margin: 0 auto;"></div>
      <p>Noen med følgende IP-adresse <i>' . $_SERVER['REMOTE_ADDR'] . '</i> har bedt om at passordet på
       brukernavn <b>' . $i->user . '</b> skal tilbakestilles.<br>
      Klikk på denne lenken for å tilbakestille passordet:<br>
      <a href="https://' . $domain . '/resetpass.php?id=' . $i->id . '&resgen=' . $resgen . '">
      https://' . $domain . '/resetpass.php?id=' . $i->id . '&resgen=' . $resgen . '</a><br>
      Om det ikke var du som ba om at passordet skulle tilbakestilles anbefales det at du ser bort fra denne mailen.
       Det kan også være det at noen har kontroll på din e-post og dermed prøver å
       få tilgang til din bruker igjennom din e-post! Hvis du er smart, oppdater ditt passord på både
        ' . $domain . ' og hos din e-post-leverandør!</p>
      </body>
      </html>
      ';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: ' . $i->user . ' <' . $i->mail . '>' . "\r\n";
            $headers .= 'From: ' . MAIL_SENDER . ' <' . HENVEND_MAIL . '>' . "\r\n";
            if (mail($to, $head, $message, $headers)) {
                $str['string'] = lykket(
                    'Det har blitt sendt en mail til mailadressen
 registrert på brukeren. Sjekk innboks/søppelpost.'
                );
            } else {
                $str['string'] = feil(
                    'Mailen kunne ikke sendes, beklager. Ta kontakt med
Ledelsen via mailadressen: <a href="mailto:werzairenet@' . MAIL_SENDER . '">system@' . MAIL_SENDER . '</a>.'
                );
            }
        } else {
            $str['string'] = feil(
                'Det ble ikke funnet noen brukere med oppgitt informasjon,
sjekk at du har skrevet riktig!'
            );
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
        include_once '../inc/pdoinc.php';
        $uid = $_POST['uid'];
        $pass = password_hash($p1, PASSWORD_BCRYPT);
        $s = $db->prepare(
            "SELECT count(*) FROM `resetpasset` WHERE `uid` = ? AND `used` = '0'
                              AND (`timestamp` + 3600) > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1"
        );
        $s->execute(
            [
                $uid
            ]
        );
        if ($s->fetchColumn() == 1) {
            $f = $db->prepare("SELECT COUNT(*) FROM `users` WHERE `id` = ? LIMIT 1");
            $f->execute([$uid]);
            if ($f->fetchColumn() == 1) {
                $f2 = $db->prepare("SELECT * FROM users WHERE id = ?");
                $f2->execute($uid);
                $obj = $db->fetch_object();
                $f3 = $db->prepare(
                    "UPDATE `users` SET `pass` = ? WHERE
                     `id` = ? LIMIT 1"
                );
                $f3->execute(
                    [
                        $p1,
                        $uid
                    ]
                );
                if ($f3->rowCount() == 1) {
                    $str['string'] = lykket(
                        'Ditt passord har blitt endret! <br>
Du kan nå logge inn på innloggingssiden med det nye passordet ditt!'
                    );
                    $str['res'] = 1;
                    /**
                     * Todo: Implement logg for password resets?
                     * $db->query("INSERT INTO `respaslogg`(`uid`,`time`,`oldpass`,`newpass`,`ip`)
                     * VALUES('$uid',UNIX_TIMESTAMP(),'{$obj->pass}','" . md5($p1) . "','$ip')"); ** Might reimplement later
                     */
                    $respw = $db->prepare(
                        "UPDATE `resetpasset` SET `used` = '1'
WHERE `uid` = ? AND `used` = '0' ORDER BY `id` DESC LIMIT 1"
                    );
                    $respw->execute([$uid]);
                    if ($respw->rowCount() == 1) {
                        $str['string'] = lykket('Passordet er oppdatert! Gå til innlogging for å fortsette!');
                    } else {
                        $str['string'] = feil('Kunne ikke oppdatere passordet, kontakt oss på ' . HENVEND_MAIL);
                    }
                } else {
                    $str['string'] = feil(
                        'Kunne ikke oppdatere passordet!
2 muligheter står:<br>Du prøvde å bruke samme passordet<br>Det var en feil i query til databasen!
<br>Send en mail til ' . HENVEND_MAIL . ' om problemet redvarer!'
                    );
                }
            } else {
                $str['string'] = feil('Brukerid-en finnes ikke!');
            }
        } else {
            $str['string'] = feil('Denne koden er ikke lengre tilgjengelig!');
        }
    } else {
        $str['string'] = feil(
            'Passordet ditt må være 4 tegn eller lengre, og være like i begge feltene under! Det kan også være at ikke riktig uid ble postet.'
        );
    }
}
print(json_encode($str));
