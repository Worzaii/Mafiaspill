<?php


namespace UserObject;

use PDO;
use UserObject\User;


class MinSide
{
    public User $user;
    public object $database;
    public string $out = "";
    private array $stats = [
        "crimetotal" => 0,
        "crimesuccess" => 0,
        "crimefailed" => 0,
        "carthefttotal" => 0,
        "cartheftsuccess" => 0,
        "cartheftfailed" => 0,
        "cartheftlost" => 0
    ];

    public function __construct(User $user, PDO $database)
    {
        $this->user = $user;
        $this->database = $database;
        $this->execute();
    }

    public function execute()
    {
        $this->setStats();
        $this->loadPage();
    }

    private function setStats()
    {
        $krimall = $this->database->prepare("SELECT count(*) FROM `krimlogg` WHERE `uid` = ?");
        $krimall->execute([$this->user->getId()]);
        $this->stats["crimetotal"] = $krimall->fetchColumn();

        $krimsuccess = $this->database->prepare("SELECT count(*) FROM `krimlogg` WHERE `uid` = ? AND `result` > '0'");
        $krimsuccess->execute([$this->user->getId()]);
        $this->stats["crimesuccess"] = $krimsuccess->fetchColumn();

        $krimfailed = $this->database->prepare("SELECT count(*) FROM `krimlogg` WHERE `uid` = ? AND `result` = '0'");
        $krimfailed->execute([$this->user->getId()]);
        $this->stats["crimefailed"] = $krimfailed->fetchColumn();

        $carsall = $this->database->prepare("SELECT count(*) FROM `carslog` WHERE `uid` = ?");
        $carsall->execute([$this->user->getId()]);
        $this->stats["carthefttotal"] = $carsall->fetchColumn();

        $carssuccess = $this->database->prepare("SELECT count(*) FROM `carslog` WHERE `uid` = ? AND `result` = '1'");
        $carssuccess->execute([$this->user->getId()]);
        $this->stats["cartheftsuccess"] = $carssuccess->fetchColumn();

        $carsfailed = $this->database->prepare("SELECT count(*) FROM `carslog` WHERE `uid` = ? AND `result` = '0'");
        $carsfailed->execute([$this->user->getId()]);
        $this->stats["cartheftfailed"] = $carsfailed->fetchColumn();

        $carslost = $this->database->prepare("SELECT count(*) FROM `carslog` WHERE `uid` = ? AND `result` = '2'");
        $carslost->execute([$this->user->getId()]);
        $this->stats["cartheftlost"] = $carslost->fetchColumn();

        #$ran = $this->database->prepare("SELECT * FROM `ransp` WHERE `uid` = '$obj->id'");
        #$ransjekk = $db->fetch_object($ran);
        #$ranlyk = $db->num_rows($db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `kl` <> 0"));
        #$ranfeil = $db->num_rows($db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `kl` = '0'"));
        #$rantjent = $db->fetch_object($ranlyk);
    }

    private function loadPage()
    {
        $prosent = $this->user->rank->progress();
        $remainxp = $this->user->rank->remaining();
        error_log("Valuetypes prosent and remainxp: ");
        error_log("Prosent: " . gettype($prosent));
        error_log("Remainxp: " . gettype($remainxp));
        global $ip;
        if ($this->user->rank->getRankID() == 12) {
            $rest = "Du har nådd høyeste rank, gratulerer! :)";
        } else {
            $rest = 1;
        }
        startpage("Mine stats");
        $carfailmiss = $this->stats["cartheftfailed"] + $this->stats["cartheftlost"];
        $this->out = <<<END
<h1>Mine stats</h1>
<table class="table" style="width:400px;">
        <tr>
            <td>Ipadresse:</td>
            <td style="width:200px">$ip</td>
        </tr>
        <tr>
            <td>Rank:</td>
            <td style="width:200px">{$this->user->rank->getRank()}(?)</td>
        </tr>
    </table>
<br>
<table style="width:400px;" class="table">
    <tr>
        <th style="padding:1px;" colspan="2">Statistikk over lykket / feilet handlinger</th>
    </tr>
    <tr>
        <td>Krim: {$this->stats["crimesuccess"]} / {$this->stats["crimefailed"]}</td>
        <td>Totalt gjennomført: {$this->stats["crimetotal"]}</td>
    <tr>
        <td>Biltyveri: {$this->stats["cartheftsuccess"]} / {$carfailmiss}</td>
        <td>Totalt gjennomført: {$this->stats["carthefttotal"]}</td>
    </tr>
    <tr>
        <td>Ran-Spiller: 0 / 0 (ikke klar)</td>
        <td>Totalt gjennomførte: 0 (ikke klar)</td>
    </tr>
    </tr>
</table>

<h1>Rankbar</h1>
<div style="margin-left: auto;margin-right: auto;width: 550px;height: 50px">
    <div style="width:550px;margin-top:10px;text-align: center;height: 40px;background: #501;position: absolute;z-index: 1;border-radius: 16px;overflow: hidden;border: 2px solid #000;">
        <div style="background: #22d300;width: $prosent%;height: 40px;border-radius: 0px;"></div>
    </div>
    <p style="padding:0;z-index: 2;position: relative;margin:0;top:22px;text-align: center">$prosent%</p>
</div>
<p>Nåværende xp: {$this->user->rank->xp}<br>
    Gjenværende xp i denne ranken: $remainxp</p>
END;

        echo $this->out;
        endpage();
    }
}