<?php

namespace UserObject\Flight;

use PDO;
use UserObject\User;

class Flight
{
    public User $user;
    public object $database;
    public string $out = "";
    
    /**
     * Flight constructor.
     *
     * @param User $user UserObject ($obj)
     * @param PDO $database Imports database from running script
     */
    public function __construct(User $user, PDO $database)
    {
        $this->user = $user;
        $this->database = $database;
        $this->execute();
    }
    
    private function execute()
    {
        if ($write = canUseFunction(1, 1)) {
            $this->out .= $write;
        } else {
            $this->readyFlight();
        }
        $this->loadPage();
    }
    
    /**
     * @uses waitText();
     * Checks whether or not User can have options listed up, if not print waitText
     */
    public function readyFlight()
    {
        $ready = "SELECT count(*) FROM `flight_log` WHERE `uid` = ? AND (timestamp + 600) > UNIX_TIMESTAMP() ORDER BY `timestamp` DESC LIMIT 0,1";
        $unready = "SELECT ((timestamp + 600) - unix_timestamp()) as timeleft FROM `flight_log` WHERE `uid` = ? AND (timestamp + 600) > UNIX_TIMESTAMP() ORDER BY `timestamp` DESC LIMIT 0,1";
        $q = $this->database->prepare($ready);
        $q->execute([$this->user->id]);
        if ($q->fetchColumn() == 1) {
            /* Have to wait until timestamp is over before next crime can be executed */
            $q2 = $this->database->prepare($unready);
            $q2->execute([$this->user->id]);
            $f = $q2->fetchColumn();
            $this->out .= $this->waitText($f);
        } elseif (isset($_POST['tilby'])) {
            $this->tryFlight($_POST['tilby']);
        } else {
            $this->getFlights();
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
        <p class="warning">Du må vente <span id="flight">' . $time . '</span> før neste flytur.</p>
        <script>
        teller(' . $time . ', "flight", false, "ned");
        </script>
        ';
    }
    
    /**
     * This will execute a POST event where "valget" is set.
     */
    public function tryFlight($choice)
    {
        if (!is_numeric($choice) || !in_array($choice, [1, 2, 3, 4, 5, 6, 7, 8])) {
            $this->out .= feil("Ugyldig verdi sendt! Prøv igjen!");
        } elseif ($this->user->getHand() < 10000) {
            $this->out .= warning("Du har ikke råd til å reise! Du må ha minst 10,000kr for å reise!");
        } else {
            $this->doFlight($choice);
        }
    }
    
    public function doFlight($choice)
    {
        $fly = $this->database->prepare("UPDATE `users` SET `hand` = (`hand` - 10000), `city` = ? WHERE `id` = ?");
        $fly->execute([$choice, $this->user->getId()]);
        if ($fly->rowCount() == 1) {
            $this->out .= lykket('Du har betalt for en billett til ' . city($choice) . ' til prisen
                                    av 10,000kr!
                                    Du må nå vente i 20 minutter før du kan reise igjen.');
            $flightlog = $this->database->prepare("insert into flight_log(uid, timestamp, from_city, to_city, price)
values (?,unix_timestamp(), ?, ?, 10000)");
            $flightlog->execute([$this->user->getId(), $this->user->getCity(), $choice]);
            error_log("Trying to add to flight log... Result: " . var_export($flightlog->fetchAll(),
                    true));
        } else {
            $this->out .= feil('Du kunne ikke reise på grunn av en feil i enten spørring eller i databasen, ta kontakt med Ledelsen!');
        }
    }
    
    /**
     * This will get all the available Crime options
     */
    public function getFlights()
    {
        $this->out .= lykket("Du er klar til å fly!");
        $this->listFlightChoices();
    }
    
    public function listFlightChoices()
    {
        $choices = "";
        for ($i = 1; $i <= 8; $i++) {
            $choices .= '
    <tr class="valg" onclick="sendpost(' . $i . ')">
    <td>Reis til ' . city($i) . '!</td>
    </tr>
    ';
        }
        $this->out .= <<<END
<p>Å ta fly vil koste deg 10,000kr.</p>
        <form method="post" action id="fly">
            <table class="table flyplass">
                <tr>
                    <th><em title="Flyplass">Velg by:</em></th>
                </tr>
                $choices
            </table>
            <input type="hidden" id="vei" value="0" name="tilby">
        </form>
        <script language="javascript">
          function sendpost(valg) {
            $('#vei').val(valg);
            $('#fly').submit();
          }

          $(document).ready(function() {
            $('.valg').hover(function() {
              $(this).find('td').removeClass().addClass('normrad1').css('cursor', 'pointer');
            }, function() {
              $(this).find('td').removeClass();
            });
          });
        </script>
        <style type="text/css">
            .valg {
                cursor: pointer;
            }
        </style>
END;
    
    }
    
    /**
     * This prints the page depending on the results of functions called.
     */
    public
    function loadPage()
    {
        startpage("Flyplass");
        echo '<h1>Flyplass</h1><img alt src="images/headers/flyplass.png">';
        echo $this->out;
        endpage();
    }
    
}