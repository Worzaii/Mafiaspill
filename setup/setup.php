#!/usr/bin/php8.4
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
include 'functions.php';
$doLoop = true;
$noselect = true;
$choice = 0;
while ($doLoop) {
    while ($noselect) {
        echo <<<TAG
\e[38;5;32mMenu:\e[0m
1: \e[38;5;40mCreate account\e[0m
2: \e[38;5;11mChange account\e[0m
3: \e[38;5;40mDelete user\e[0m
4: \e[38;5;40mChange password for user\e[0m
Q: \e[38;5;196mQuit program\e[0m \n\n
TAG;
        $choice = readline("Make a choice: (1|2|3|4|q): ");
        if (in_array($choice, [1, 2, 3, 4])) {
            $noselect = false;
        } elseif (strtolower($choice) == "q") {
            $noselect = false;
            $doLoop = false;
            echo "Script exiting...\n";
        } else {
            echo "\nThat's not a valid choice, try again\n\n";
        }
    }
    if ($choice == 1) {
        $user = readline("Username: ");
        $regex = preg_match("/^[a-z0-9\-_]{3,10}$/i", $user);
        if ($regex) {
            echo "Username valid!\n";
            $pass = readline("Write in a password or let empty for auto-generated: ");
            if (strlen($pass) == 0) {
                $genpas = genpass();
                echo "You didn't set a password, generating...\n\n";
                echo "Password: \e[1m" . $genpas . "\e[0m\n\n";
            }
            echo "\e[0m\nSelect user account level?
1=\e[38;5;51mAdministrator\e[0m
2=\e[38;5;46mModerator\e[0m,
3=\e[38;5;33mForum-moderator\e[0m,
4=\e[38;5;15mDefault user account\e[0m
5=\e[38;5;9mNPC\e[0m
Status: ";
            $status = readline(
                ""
            );
            if (in_array($status, [1, 2, 3, 4, 5])) {
                $inpass = (isset($genpas)) ? $genpas : $pass;
                $exists = $db->prepare("select count(*) from users where user = ?");
                $exists->execute([$user]);
                if ($exists->fetchColumn() != 1) {
                    $userq = $db->prepare(
                        "insert into users(user, pass, status, regstamp, mail) values(?, ?, ?, ?, ?)"
                    );
                    $userq->execute(
                        [
                            $user,
                            password_hash($inpass, PASSWORD_DEFAULT),
                            $status,
                            time(),
                            "noreply@invalidmail.com"
                        ]
                    );
                    echo "\nCommand executed!\nAffected rows: " . $userq->rowCount() . PHP_EOL .
                        "Account is ready to log on\n\n";
                } else {
                    echo "Username is taken! Try another one!\n\n";
                }
            } else {
                echo "\nNot a valid choice!\n\n";
            }
        } else {
            echo "Username didn't match criteria.\n\n";
        }
        $choice = 0;
    } elseif ($choice == 2) {
        echo "::Change user::\n\n";
        $user = readline("Type username: ");
        echo "You wrote '$user', continue?\n";
        $svar = readline("Yes or no: (q to go back to main menu):");
        if (strtolower($svar) == "ja") {
            /* Continues execution */
            echo "Checking if user account exists...\n";
            $check = $db->prepare("select count(*) from users where user = ?");
            if ($check->execute([$user])) {
                if ($check->fetchColumn() == 1) {
                    echo "Account found, next?\n\n";
                    $notchosen = true;
                    while ($notchosen) {
                        echo <<<END
1: Change bank values
2: Change account level
3: Change support status
4: Reset countdowns (crime, car theft, jail, airport, all)

END;
                        $endrechoice = (int)readline("Select number: ");
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
                                        /**
                                         * TODO: Either remove function or implement it
                                         */
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
        echo "Ønsker du virkelig å slette en brukerkonto? Dette sletter kun selve entryen i brukertabellen og vil ikke være mulig å gjenopprette når det er utført.\n\n";
        $bekreft = readline("Hvis du er sikker på at du vil fortsette, skriv: 'jeg bekrefter': ");
        if (strtolower($bekreft) == 'jeg bekrefter') {
            $bruker = readline("Oppgi brukernavn du ønsker å slette: ");
            $checkifexists = $db->prepare("select count(*) from users where user = ?");
            if ($checkifexists->execute([$bruker])) {
                /* Literally checking if any user accounts has the name as given, case insensitive */
                $numrows = $checkifexists->fetchColumn();
                if ($numrows == 1) {
                    /* Found one count of user, getting data next */
                    $getuser = $db->prepare("select * from users where user = ?");
                    if ($getuser->execute([$bruker])) {
                        /* Getting user data */
                        $userinfo = $getuser->fetchObject();
                        echo <<<DATA
::Brukerdata hentet::
BrukerID:       $userinfo->id
Brukernavn:     $userinfo->user
Passord:        $userinfo->pass
E-post:         $userinfo->mail
Bildelenke:     $userinfo->image
Profiltekst slettes fullstendig og hentes ikke inn her...
Familie:        $userinfo->family
Bankverdi:      $userinfo->bank
Handverdi:      $userinfo->hand
By:             $userinfo->city
Våpen:          $userinfo->weapon
Kuler:          $userinfo->bullets
Poeng:          $userinfo->points
Erfaringspoeng: $userinfo->exp
Status:         $userinfo->status
Supportstatus:  $userinfo->support
IP-adresse:     $userinfo->ip
RegistreringsIP:$userinfo->regip
Hostname:       $userinfo->hostname
Reg hostname:   $userinfo->reghostname
Last active:    $userinfo->lastactive
Tving avlogg:   $userinfo->forceout (Denne verdien er normalt 0).
Reg. tid:       $userinfo->regstamp
Picmakerstatus: $userinfo->picmaker (Denne verdien er normalt 0).

All data hentet.

Hvis du er sikker på at denne dataen nå skal slettes...
DATA;
                        $sistebekreft = readline("Skriv 'jeg bekrefter sletting': ");
                        if (strtolower($sistebekreft) == 'jeg bekrefter sletting') {
                            $preparedelete = $db->prepare(
                                "delete from users where id = ? and user = ? and status = ? limit 1"
                            );
                            if ($preparedelete->execute(
                                [
                                    $userinfo->id,
                                    $userinfo->user,
                                    $userinfo->status
                                ]
                            )) {
                                if ($preparedelete->rowCount() == 1) {
                                    echo "Brukerkontoen har blitt slettet. Ta vare på informasjonen over om det på et tidspunkt blir nødvendig å legge inn dataene på nytt.\n\n";
                                } else {
                                    echo "Merkelig nok ble ingen rader berørt av utføringen... Sjekk om bruker eksisterer ved å sjekke i databasen manuelt.\n\n";
                                }
                            } else {
                                echo "Kunne ikke utføre sletting av brukerkonto!\n" . $preparedelete->errorCode() . ": " . $preparedelete->errorInfo();
                            }
                        } else {
                            echo "Du skrev ikke riktig tekst. Hvis du skrev feil så må du gjenta hele prosessen, om ikke vil det bli tatt som at du ønsket å avbryte slettingen av dataene. Returnerer til hovedmeny...\n\n";
                        }
                    } else {
                        echo "Utføringen av kommandoen fungerte ikke!\n" . $preparedelete->errorCode() . ": " . $preparedelete->errorInfo();
                    }
                } else {
                    echo "Fant $numrows brukere på $bruker. Kan ikke fortsette, går til hovedmeny...\n\n";
                }
            } else {
                echo "Kunne ikke sjekke om brukerkonto eksisterer.\n" . $preparedelete->errorCode() . ": " . $preparedelete->errorInfo();
            }
        } else {
            echo "Enten skrev du ikke rett eller ønsket å avbryte slettingen. Du må velge fra hovedmenyen på nytt om du ønsker å slette.\n\n";
        }
        $bekreft = "";
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
            $newpass = (strlen($newpass) <= 3) ? genpass() : $newpass;
            $update = $db->prepare("update users set pass = ? where $queryadd = ?");
            if ($update->execute(
                [
                    password_hash($newpass, PASSWORD_DEFAULT),
                    $value
                ]
            )) {
                if ($update->rowCount() == 1) {
                    echo "Passordet har blitt satt til: " . $newpass;
                }
            } else {
                echo "Kunne ikke oppdatere passordet! " . $update->errorInfo();
            }
        }
        $choice = 0;
    } else {
        $noselect = true;
    }
}
