<?php
global $db, $obj;
$ant = $GLOBALS["stored_queries"]["online"];
$sql3 = $db->query("SELECT COUNT(*) as `numrows` FROM `chat`");
$num2 = $sql3->fetchObject()->numrows;
$ant2 = $GLOBALS["stored_queries"]["jail"];
#error_log("Antall i fengsel lagret i array: " . $ant2);
$klpre = $db->prepare("SELECT timewait FROM `krimlogg` WHERE `uid` = ? AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `timestamp` DESC LIMIT 0,1");
$klpre->execute([$obj->id]);
if ($res = $klpre->fetchColumn()) {
    $ktl = (($res - time()) >= 1) ? ($res - time()) : null;
} else {
    $ktl = null;
}
$clpre = $db->prepare("SELECT `timewait` FROM `carslog` WHERE `uid` = ? AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 0,1");
$clpre->execute([$obj->id]);
if ($wait = $clpre->fetchColumn()) {
    $btl = (($wait - time()) >= 1) ? ($wait - time()) : null;
} else {
    $bt = null;
    $btl = null;
}
$rlpre = $db->prepare("SELECT timestamp FROM `rob_log` WHERE `uid` = ? AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
$rlpre->execute([$obj->id]);
if ($wait = $rlpre->fetchColumn()) {
    $rtl = (($wait - time()) >= 1) ? ($wait - time()) : null;
} else {
    $rt = null;
    $rtl = null;
}
$jailself = $db->prepare("SELECT timeleft FROM `jail` WHERE `uid` = ? AND `timeleft` > UNIX_TIMESTAMP() AND `breaker` IS NULL ORDER BY `id` DESC LIMIT 1");
$jailself->execute([$obj->id]);
if ($jail = $jailself->fetchObject()) {
    $jte = (($jail->timeleft - time()) >= 1) ? ($jail->timeleft - time()) : null;
} else {
    $jt = null;
    $jte = null;
}
$onl = "online.php";
?>
<h2>Rank</h2>
<ul>
    <?php
    if ($ktl == null) {
        echo '<li><a href="krim.php">Kriminalitet</a>';
    } else {
        echo '<li><a href="krim.php">Kriminalitet</a> <span style="font-size:10px;" id="krimteller">' . $ktl . '</span>
<script>loggteller(' . $ktl . ',"krimteller",false,"ned");</script></li>';
    }
    if ($btl == null) {
        echo '<li><a href="biltyveri.php">Biltyveri</a>';
    } else {
        echo '<li><a href="biltyveri.php">Biltyveri</a> <span style="font-size:10px;" id="bilteller">' . $btl . '</span>
<script>loggteller(' . $btl . ',"bilteller",false,"ned");</script></li>';
    }
    if ($rtl == null) {
        echo '<li><a href="stjel.php">Ran Spiller</a>';
    } else {
        echo '<li><a href="stjel.php">Ran Spiller</a> 
<span style="font-size:10px;" id="ranteller">' . $rtl . '</span>
<script>loggteller(' . $rtl . ',"ranteller",false,"ned");</script></li>';
    }
    if ($jte == null) {
        echo '<li><a href="fengsel.php">Fengsel</a>';
        if ($ant2 >= 1) {
            echo "($ant2)";
        }
        echo '</li>';
    } else {
        echo '<li><a href="fengsel.php">Fengsel</a> <span style="font-size:10px;" id="jailteller">' . $jte . '</span>
<script>loggteller(' . $jte . ',"jailteller",false,"ned");</script></li>';
    }
    ?>
    <li><a href="fly.php">Flyplass</a></li>
    <li><a href="#Drap">Drap </a></li>
    <li><a href="#oppdrag.php">Oppdrag</li>
    <li><a href="#Ran">Ran </a></li>
</ul>
<h2>Verdier</h2>
<ul>
    <li><a href="#Marked">Svartebørsen </a></li>
    <li><a href="#Bunker">Bunker </a></li>
    <li><a href="bank.php">Banken </a></li>
    <li><a href="#Poeng">Poeng </a></li>
    <li><a href="#verving.php">Verving </a></li>
    <li><a href="#Auksjon">Auksjon </a></li>
    <li><a href="#Firmaer">Firmaer </a></li>
    <li><a href="garasje.php">Garasje </a></li>
</ul>
<h2>Kommunikasjon</h2>
<ul>
    <li><a href="innboks.php">Innboks <?php echo "\u{2709}"; ?></a></li>
    <li><a href="#deputy.php">Send inn søknad!</a></li>
    <li><a href="#support.php">Support</a></li>
    <li><a href="<?=$onl;?>">Spillere pålogget</a> (<?=$ant;?>)</li>
    <li><a href="nyheter.php">Nyheter</a></li>
    <li><a href="ledelse.php">Ledelsen</a></li>
</ul>
<h2>Sosialt</h2>
<ul>
    <li><a href="chat.php">Chat</a><?php
        if ($num2 >= 2) {
            echo "($num2)";
        }
        ?></li>
    <li><a href="nyforum.php?type=1">Generelt Forum </a></li>
    <li><a href="nyforum.php?type=2">Salg og Søknadsforum </a></li>
    <li><a href="nyforum.php?type=3">Annet </a></li>
    <?php
    if ($obj->family != null) {
        echo '<li><a href="familiepanel.php?side=konfam">Gjengen </a></li>';
    } else {
        echo '<li><a href="Familie">Gjengene </a></li>';
    }
    ?>
</ul>
<h2>Gambling</h2>
<ul>
    <li><a href="Lotto">Lotto </a></li>
    <li><a href="bj.php">Blackjack </a></li>
</ul>