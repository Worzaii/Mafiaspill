<?php

namespace UserObject\Crime;

use PDO;
use UserObject\User;

class Crime extends \mainclass
{
    protected function execute()
    {
        if ($write = canUseFunction(1, 1)) {
            $this->out .= $write;
        } else {
            $this->readyCrime();
        }
        $this->loadPage();
    }

    /**
     * @uses waitText();
     * Checks whether or not User can have options listed up, if not print waitText
     */
    public function readyCrime()
    {
        $ready = "SELECT count(*) FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1";
        $unready = "SELECT timewait FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1";
        $q = $this->database->prepare($ready);
        $q->execute([$this->user->id]);
        if ($q->fetchColumn() == 1) {
            /* Have to wait until timeleft is over before next crime can be executed */
            $q2 = $this->database->prepare($unready);
            $q2->execute([$this->user->id]);
            $f = $q2->fetchColumn();
            $this->out .= $this->waitText($f);
        } elseif (isset($_POST['valget'])) {
            $this->tryCrime($_POST['valget']);
        } else {
            $this->getCrime();
        }
    }

    /**
     * @param $time
     *
     * @return string "Prepared text for waiting"
     */
    public function waitText($time)
    {
        return '
        <p class="warning">Du må vente <span id="krim">' . ($time - time()) . '</span> før neste krim.</p>
        <script>
        teller(' . ($time - time()) . ', "krim", false, "ned");
        </script>
        ';
    }

    /**
     * This will execute a POST event where "valget" is set.
     */
    public function tryCrime($choice)
    {
        if (!is_numeric($choice)) {
            $this->out .= feil("Ugyldig verdi sendt! Prøv igjen!");
        } else {
            $crime = "select count(*) from crime where id = ?";
            $execute = $this->database->prepare($crime);
            $execute->execute([$choice]);
            if ($execute->fetchColumn() == 1) {
                $this->doCrime($choice);
            } else {
                $this->out .= feil("Valg eksisterer ikke! Prøv igjen!");
            }
        }
    }

    public function doCrime($choice)
    {
        $crime = $this->database->prepare("select * from crime where id = ?");
        $crime->execute([$choice]);
        $info = $crime->fetchObject();
        $chance = $this->database->prepare("select * from chance where uid = ? and `option` = ? and type = '1'");
        $chance->execute([$this->user->getId(), $choice]);
        $thechance = $chance->fetchObject();
        if ($thechance->chance >= 74) {
            $ran2 = rand(10, 46);
            $chanceupd = $this->database->prepare("UPDATE `chance` SET `chance` = (`chance` - ?) WHERE `uid` = ? AND `option` = ? LIMIT 1");
            $chanceupd->execute([$ran2, $this->user->getId(), $choice]);
            $thechance->chance -= $ran2;
        } elseif ($thechance->chance <= 73) {
            $ran2 = rand(1, 3);
            $chanceupd = $this->database->prepare("UPDATE `chance` SET `chance` = (`chance` + ?) WHERE `uid` = ? AND `option` = ? AND `chance` < '80'");
            $chanceupd->execute([$ran2, $this->user->getId(), $choice]);
            $thechance->chance += $ran2;
        }
        $kr = mt_rand($info->minval, $info->maxval);
        $timewait = $info->untilnext + time();
        if (mt_rand(0, 100) <= $thechance->chance) {
            $prep = $this->database->prepare("UPDATE `users` SET `hand` = (`hand` + ?),`exp` = (`exp` + ?) WHERE `id` = ? LIMIT 1");
            $prep->execute([
                $kr,
                $info->expgain,
                $this->user->getId()
            ]);
            $rows = $prep->rowCount();
            if ($rows == 1) {
                $secondprep = $this->database->prepare("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES(?,UNIX_TIMESTAMP(),?,?,(? + UNIX_TIMESTAMP()))");
                if ($secondprep->execute(
                    [
                        $this->user->getId(),
                        $choice,
                        $kr,
                        $info->untilnext
                    ]
                )) {
                    $this->out .= '
                        <p class="lykket">Du var heldig og fikk ' . number_format($kr) . 'kr med deg!</p>
                        <p class="feil">Tid til neste krim: <span id="krim">' . $info->untilnext . '</span>.</p>
                        <script>
                        teller(' . $info->untilnext . ', "krim", false, \'ned\');
                        </script>
                    ';
                } elseif (r1()) {
                    $this->out .= '
                <p>Feil i spørring2: ' . var_export($this->database->errorInfo()) . '</p>
                ';
                } else {
                    $this->out .= feil("Det var feil i utførelse av spørringer, vennligst rapporter dette til support, slik at de kan se i loggen hva som hendte!");
                }
            } elseif (r1()) {
                $this->out = feil('Feil i spørring: ' . var_export($this->database->errorInfo(),
                        true));
            } else {
                $this->out = feil('Det var feil i utførelse av spørringer, vennligst rapporter dette til support!');
            }
        } else {
            $failed = $this->database->prepare("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES(?,UNIX_TIMESTAMP(),?,'0',?)");
            $failed->execute([$this->user->getId(), $choice, $timewait]);
            if ($failed->rowCount() == 1) {
                $fen = mt_rand(0, 3);
                if ($fen !== 2) {
                    $this->out .= feil('Du klarte det ikke! <br>Tid til neste krim: <span id="krim">' . $info->untilnext . '</span>.') . '
              <script>
              teller(' . $info->untilnext . ', "krim", false, \'ned\');
              </script>
              ';
                } else {
                    $time = time();
                    $time2 = time() + $timewait;
                    $punish = $time + $info->punishtime;
                    $q = $this->database->prepare("INSERT INTO `jail`(`uid`,`reason`,`timestamp`,`timeleft`,`priceout`) VALUES(?,?,UNIX_TIMESTAMP(),?,?)");
                    $q->execute([
                        $this->user->getId(),
                        "Prøvde å være litt kriminiminel",
                        $punish,
                        2500000
                    ]);
                    $this->out .= feil('Du klarte det ikke, og politiet oppdaget deg!');
                    if ($q->rowCount() == 1) {
                        $this->out .= feil('Du ble satt i fengsel! <br>Gjenstående tid: <span id="krim2">' . ($info->punishtime) . '</span>.<script>teller(' . $info->punishtime . ', "krim2", false, \'ned\');</script>');
                        $jailed = true;
                    } else {
                        $this->out .= feil('Klarte ikke å sette deg i fengsel! Så bra...');
                    }
                }
            }
        }
    }

    /**
     * This will get all the available Crime options
     */
    public function getCrime()
    {
        $this->out .= lykket("Du er klar til å utføre kriminalitet!");
        $getCrimes = "select * from crime where levelmin <= ? ORDER BY `levelmin` DESC,`id` DESC";
        $q1 = $this->database->prepare($getCrimes);
        $q1->execute([$this->user->exp->getRankID()]);
        $this->listCrimeChoices();
    }

    public function listCrimeChoices()
    {
        $get_actions = $this->database->prepare("select * from crime where levelmin <= ? ORDER BY `levelmin` DESC,`id` DESC");
        $get_actions->execute([$this->user->exp->getRankID()]);
        $this->out .= '
        <form name="krim"
              method="post"
              id="krim"
              action="">
            <table style="width:590px;"
                   class="table">
                <tr>
                    <th colspan="5">Krimhandlinger</th>
                </tr>
                <tr>
                    <td style="width:250px;"><b>Handling</b></td>
                    <td><b>Fortjeneste</b></td>
                    <td><b>Ventetid</b></td>
                    <td><b>Sjanse</b></td>
                    <td><b>Straff</b></td>
                </tr>';
        while ($r = $get_actions->fetchObject()) {
            $sql2 = $this->database->prepare("SELECT count(*) FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
            $sql2->execute([$this->user->id, $r->id]);
            if ($sql2->fetchColumn() >= 1) {
                $sql3 = $this->database->prepare("SELECT * FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
                $sql3->execute([$this->user->id, $r->id]);
                $res = $sql3->fetchObject();
                $sjanse = $res->chance . '%';
            } else {
                $new_chance = $this->database->prepare("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES(?,'1',?)");
                $new_chance->execute([$this->user->id, $r->id]);
                $sjanse = "0%";
            }
            $this->out .= '<tr class="valg" onclick="sendpost(' . $r->id . ')">
<td>' . htmlentities($r->description, ENT_NOQUOTES | ENT_HTML401,
                    "UTF-8") . '</td><td>' . number_format($r->minval) . '-' . number_format($r->maxval) . 'kr</td><td>' . $r->untilnext . ' sekunder</td><td>' . $sjanse . '</td><td>' . $r->punishtime . ' sekunder</td>
</tr>';
        }
        $this->out .= <<<END
</table>
            <input type="hidden"
                   value=""
                   name="valget"
                   id="valget">
        </form>
        <script>
          function sendpost(valg) {
            $('#valget').val(valg);
            $('#krim').submit();
          }

          $(document).ready(function() {
            $('.valg').hover(function() {
              $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
            }, function() {
              $(this).removeClass().css('cursor', 'pointer');
            });
          });
        </script>
END;
    }

    /**
     * This prints the page depending on the results of functions called.
     */
    public function loadPage()
    {
        startpage("Kriminalitet");
        echo '<h1>Kriminalitet</h1><img alt src="images/headers/krim.png"><p>Når du først starter med kriminalitet,
 så vil du kun ha et valg. Ettersom du kommer opp i rank, så vil nye valg låses opp.
 Hvis du ikke ser noen valg, kontakt support!</p>';
        echo $this->out;
        endpage();
    }

}
