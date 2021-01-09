<?php
include("core.php");
startpage("Chat");
echo "<h1>Chat - Snakk med andre spillere</h1>";

if (r1() || r2() || r3()) {
    $dis = <<<END
<a href="tomprat.php" onclick="return confirm('Sikker på at du vil tømme prat?');">Tøm prat</a>
END;
    $dis2 = <<<END
<a href="visprat.php">Vis hele prat databasen</a>
END;

    echo info($dis);
    echo info($dis2);
}

$s = $db->prepare("SELECT count(*) FROM `forumban` WHERE `uid` = ? AND `bantime` > UNIX_TIMESTAMP() AND `active` = '1' ORDER BY `bantime` DESC LIMIT 1");
$s->execute([
    $obj->id
]);

if ($s->fetchColumn() == 1) {
    $s2 = $db->prepare("SELECT bantime,uid,banner,reason FROM `forumban` WHERE `uid` = ? AND `bantime` > UNIX_TIMESTAMP() AND `active` = '1' ORDER BY `bantime` DESC LIMIT 1");
    $s2->execute([
        $obj->id
    ]);
    $f = $s2->fetchObject();
    $timeleft = $f->bantime - time();
    $banner = user($f->banner);
    $uid = "user" + $f->uid;
    $grunn = $f->reason;
    echo <<<HTML
	<div style="border-width: 2px; border-style: dotted; border-color: red; "><p class="feil"> Du er utestengt fra forumet!</p>
	<br><b>Du har blitt utestengt av forumet av $banner og derfor kan du se chatten men uten tilgang til å skrive.<br>
    Du har </h3><span id="$uid"></span><script>teller($timeleft,"$uid","true","ned");</script> igjen av straffen din. Hvis du mener du har fått den ved en feiltagelse, kontakt Support!<br><br>
	Grunnen for at du ble utestengt er: $grunn</b></div><br>
HTML;
} else {
    echo <<<HTML
    <br>
    <input name="write" type="text" id="write" placeholder="Enter for å skrive">
    <br>
HTML;
}
echo <<<HTML
    <div id="praten"></div>
    <script src="js/prat.js" type="text/javascript"></script>
HTML;
endpage();
