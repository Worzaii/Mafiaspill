<?php
/**
 * todo: Make a CLI script to create users fast. With options of status and more.
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
        $choice = readline("Gjør et valg: (1|2|3|4): ");
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
        $regex = preg_match("/^[a-z0-9-_]{3,10}$/i", $user);
        if ($regex) {
            echo "Brukernavn gyldig!\n";
            $pass = readline("Opprett et passord, eller la stå tomt for tilfeldig generert passord: ");
            if (strlen($pass) == 0) {
                /* Generating random password: */
                $pass = "abcdefghijklmnopqrstuvwxyz0123456789-_.!\"#%&\\/()=?";
                $genpas = "";
                for ($i = 0; $i < 12; $i++) {
                    $genpas .= $pass[rand(0, strlen($pass) - 1)];
                }
                echo "Passord generert: " . $genpas . PHP_EOL . PHP_EOL;
            }
            $status = readline("Hvilket tilgangsnivå skal brukeren ha? \n1=admin, 2=moderator, 3=forum moderator, 4 = ? 5=vanlig bruker (standard): ");
            /*echo "\n\ntypes: user: " . gettype($user) . "\nIs it in array? " . ((in_array($status,
                    [1, 2, 3, 4, 5])) ? "Ja" : "Nei");*/
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
                    echo "Kommando utført!\nAntall rader endret: " . $userq->rowCount().PHP_EOL . "Med andre ord, brukerkontoen har blitt opprettet og kan allerede nå logge på.";
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
    } else {
        $noselect = true;
    }
}