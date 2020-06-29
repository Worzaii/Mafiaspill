#!/usr/bin/php7.4
<?php
if (php_sapi_name() != "cli") {
    /*If someone were to try running it from the browser, it would stop the entirety of PHP execution, so stopping it here already to be safe*/
    die("This script must be run in Command line!");
}
chdir(dirname(__FILE__)); # This allows the script to be run from wherever php started if from.
/**
 * todo: Customize several commands to use in different scenarios.
 */
define('BASEPATH', true);
include '../system/config.php';
include '../inc/pdoinc.php';
$doLoop = true;
$noselect = true;
$choice = 0;
while ($doLoop) {
    while ($noselect) {
        echo "Meny: 
1: Opprett bruker
2: Endre bruker
3: Slett bruker
4: Endre passord for bruker
Q: Avslutt program (Alternativt, bruk CTRL + D)\n\n";
        $choice = readline("Gjør et valg: (1|2|3|4|q): ");
        if (in_array($choice, [1, 2, 3, 4])) {
            $noselect = false;
        } elseif (strtolower($choice) == "q") {
            $noselect = false;
            $doLoop = false;
            echo "Script avsluttes...\n";
        } else {
            echo "\nDet er ikke et valg! Prøv igjen!\n\n";
        }
    }
    if ($choice == 1) {
        $user = readline("Brukernavn: ");
        $regex = preg_match("/^[a-z0-9\-_]{3,10}$/i", $user);
        if ($regex) {
            echo "Brukernavn gyldig!\n";
            $pass = readline("Opprett et passord, eller la stå tomt for tilfeldig generert passord: ");
            if (strlen($pass) == 0) {
                echo "Du laget ikke passord selv, generer et for deg...\n\n";
                /* Generating random password: */
                $pass_chars = "abcdefghijklmnopqrstuvwxyz0123456789-_.!\"#%&\\/()=?";
                $genpas = "";
                for ($i = 0; $i < 12; $i++) {
                    $genpas .= $pass_chars[rand(0, strlen($pass_chars) - 1)];
                }
                echo "Passord generert: " . $genpas . "\n\n";
            }
            $status = readline("\nHvilket tilgangsnivå skal brukeren ha? \n1=admin, 2=moderator, 3=forum moderator, 4 = picmaker (vanlig bruker), 5=vanlig bruker: ");
            if (in_array($status, [1, 2, 3, 4, 5])) {
                $inpass = (isset($genpas)) ? $genpas : $pass;
                $exists = $db->prepare("select count(*) from users where user = ?");
                $exists->execute([$user]);
                if ($exists->fetchColumn() != 1) {
                    $userq = $db->prepare("insert into users(user, pass, status, regstamp, mail) values(?, ?, ?, ?, ?)");
                    $userq->execute([
                        $user,
                        password_hash($inpass, PASSWORD_DEFAULT),
                        $status,
                        time(),
                        "user@localhost.localdomain"
                    ]);
                    echo "\nKommando utført!\nAntall rader endret: " . $userq->rowCount() . PHP_EOL . "Med andre ord, brukerkontoen har blitt opprettet og kan allerede nå logge på.\n\n";
                } else {
                    echo "Brukernavn eksisterer allerede! Forsøk et annet brukernavn!\n\n";
                }
            } else {
                echo "\nDet var ikke et gyldig valg!\n\n";
            }
        } else {
            echo "Brukernavnet er ikke innenfor, prøv igjen!\n\n";
        }
        $choice = 0;
    } elseif ($choice == 2) {
        echo "::Endre bruker::\n\n";
        $user = readline("Oppgi bruker som skal endres (brukernavn): ");
        echo "Du oppga '$user', stemmer det?\n";
        $svar = readline("Ja eller nei (q for å avslutte og returnere til hovedmeny):");
        if (strtolower($svar) == "ja") {
            /* Continues execution */
            echo "Sjekker om brukerkonto eksisterer...\n";
            $check = $db->prepare("select count(*) from users where user = ?");
            if ($check->execute([$user])) {
                if ($check->fetchColumn() == 1) {
                    echo "Bruker funnet, hva ønsker du å gjøre videre?\n\n";
                    $notchosen = true;
                    while ($notchosen) {
                        echo <<<END
1: Legg til eller fjern verdier fra bank
2: Endre status
3: Endre supportspillerstatus
4: Nullstill timere (krim, biltyveri, fengsel, flyplass, alle)

END;
                        $endrechoice = (int)readline("Skriv tall: ");
                        if (in_array($endrechoice, [1, 2, 3, 4])) {
                            $notchosen = false;
                            if ($endrechoice == 1) {
                                /**
                                 * Bank handling depending on input
                                 */
                                $info = $db->prepare("select bank from users where user = ?");
                                if ($info->execute([$user])) {
                                    $bankvalue = $info->fetchColumn();
                                    echo "Skriv = for å sette nøyaktig sum i banken, + for å legge til verdi i banken eller - for å trekke ifra.\n\n";
                                    $bank = readline("Skriv verdi");
                                    if ($bank[0] == '=') {
                                        /**
                                         * Setting bank value
                                         * Todo: Make a query to set said value without the = sign.
                                         */
                                        $updateuser = $db->prepare("update users set bank = ? where user = ?");
                                        if ($updateuser->execute([ltrim($bank, "="), $user])) {
                                            echo "Ny bankverdi for $user satt til $bank! \n\n";
                                        } else {
                                            echo "Kunne ikke sette ny verdi! Feilmelding: \n" . $updateuser->errorCode() . ": " . $updateuser->errorInfo() . "\n\n";
                                        }
                                    } elseif ($bank[0] == '-') {
                                        /* Removing said amount from player */
                                    } elseif ($bank[0] == '+') {
                                        /* Adding said amount from player */
                                    } else {
                                        echo "Verdi angitt er ugyldig!\n\n";
                                    }
                                }
                            } elseif ($endrechoice == 2) {
                                /**
                                 * Change a user's status
                                 */
                                $getuser = $db->prepare("select user, regstamp, status from users where user = ?");
                                if ($getuser->execute([$user])) {
                                    echo "Brukerdata hentet.\n\n";
                                    $userinfo = $getuser->fetchObject();
                                    $username = $userinfo->user;
                                    $reg = date("H:i:s d.m.Y", $userinfo->regstamp);
                                    $status = $userinfo->status;
                                    echo <<<END
Brukernavn:         $username
Registrert den:     $reg
Nåværende status:   $status
END;
                                    echo "\n\n";
                                    $newstatus = (int)readline("Oppgi ny status: ");
                                    echo "Gammel status var $status, ny status blir $newstatus\n";
                                    $bekreft = readline("Bekreft endring (ja|nei): ");
                                    if (strtolower($bekreft) == "ja") {
                                        echo "Bekreftet... Utfører...\n";
                                        $updateuser = $db->prepare("update users set status = ? where user = ?");
                                        if ($updateuser->execute([$newstatus, $user])) {
                                            echo "$username har blitt satt til status $newstatus!\n\n";
                                        } else {
                                            /**
                                             * Couldn't update user status. Show error:
                                             */
                                            echo "Kunne ikke oppdaterer status: " . $updateuser->errorCode() . ": " . $updateuser->errorInfo() . "\n\n\n";
                                        }
                                    } else {
                                        echo "Avbryter endring og går tilbake til hovedmeny.\n\n";
                                        $choice = 0;
                                        $endrechoice = 0;
                                        $notchosen = false;
                                    }
                                } else {
                                    echo "Kunne ikke hente brukerdata: " . $updateuser->errorCode() . ": " . $updateuser->errorInfo() . "\n\n\n";
                                }
                                $choice = 0;
                                $endrechoice = 0;
                                $notchosen = false;
                            } elseif ($endrechoice == 3) {
                                /**
                                 * Change support player status
                                 */

                            } elseif ($endrechoice == 4) {
                                /**
                                 * Reset player timers from list or just all.
                                 */
                            }
                        } else {
                            echo "Prøv igjen...\n\n";
                        }
                    }
                } else {
                    echo "Kunne ikke finne noen brukere med brukernavnet $user\n\n";
                }
            }
        } elseif (strtolower($svar) == "nei") {
            /* Loop execution by requery */
            $user = "";
            $svar = "";
        } elseif (strtolower($svar) == "q") {
            echo "Avslutter ::Endre bruker::\n\n\n";
            $choice = 0;
        }
    } elseif ($choice == 3) {
        echo "You want to delete a user?! Heresy!!!!\n\n\n";
        $choice = 0;
    } elseif ($choice == 4) {
        echo "La brukernavn stå tom hvis du ønsker å bruke ID i stedet.\n";
        $brukernavn = readline("Brukernavn: ");
        if (strlen($brukernavn) == 0) {
            $id = readline("ID: ");
        }
        $queryadd = (strlen($brukernavn) >= 1) ? "user" : "id";
        $value = (strlen($brukernavn) >= 1) ? $brukernavn : $id;
        $query = $db->prepare("select count(*) from users where $queryadd = ?");
        $query->execute([$value]);
        if ($query->fetchColumn() == 1) {
            /* Allows password change... */
            echo "La stå for automatisk generert passord.\n";
            $newpass = readline("Nytt passord: ");
            if (strlen($newpass) >= 3) {
                $pass_chars = "abcdefghijklmnopqrstuvwxyz0123456789-_.!\"#%&\\/()=?";
                $newpass = "";
                for ($i = 0; $i < 12; $i++) {
                    $newpass .= $pass_chars[rand(0, strlen($pass_chars) - 1)];
                }
                $update = $db->prepare("update users set pass = ? where $queryadd = ?");
                if ($update->execute([
                    $newpass,
                    $value
                ])) {
                    if ($update->rowCount() == 1) {
                        echo "Passordet har blitt satt til: " . $newpass;
                    }
                } else {
                    echo "Kunne ikke oppdatere passordet! " . $update->errorInfo();
                }

            } else {
                $pass_chars = "abcdefghijklmnopqrstuvwxyz0123456789-_.!\"#%&\\/()=?";
                $newpass = "";
                for ($i = 0; $i < 12; $i++) {
                    $newpass .= $pass_chars[rand(0, strlen($pass_chars) - 1)];
                }
            }
        }
        $choice = 0;

    } else {
        $noselect = true;
    }
}
