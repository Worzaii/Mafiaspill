<?php
global $db, $obj, $set;
$sql = $db->query("SELECT user FROM `users` WHERE `lastactive` BETWEEN '" . (time() - 1800) . "' AND '" . time() . "' ORDER BY `lastactive` DESC");
$ant = $db->num_rows();
$sql3 = $db->query("SELECT * FROM `chat`");
$num2 = $db->num_rows();
$sql4 = $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
$ant2 = $db->num_rows();
$db->query("SELECT * FROM `krimlogg` WHERE `uid` = '$obj->id' AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `timestamp` DESC LIMIT 0,1");
if ($db->num_rows() == 1) {
    $kt = $db->fetch_object();
    $ktl = (($kt->timewait - time()) >= 1) ? ($kt->timewait - time()) : null;
} else {
    $ktl = null;
}
$db->query("SELECT * FROM `carslog` WHERE `uid` = '$obj->id' AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 0,1");
$numrows = $db->num_rows();
if ($numrows >= 1) {
    $bt = $db->fetch_object();
    $btl = (($bt->time - time()) >= 1) ? ($bt->time - time()) : null;
} else {
    $bt = null;
    $btl = null;
}
$db->query("SELECT * FROM `rob_log` WHERE `uid` = '$obj->id' AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
$ranrows = $db->num_rows();
if ($ranrows >= 1) {
    $rt = $db->fetch_object();
    $rtl = (($rt->timestamp - time()) >= 1) ? ($rt->timestamp - time()) : null;
} else {
    $rt = null;
    $rtl = null;
}
$db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `timeleft` > UNIX_TIMESTAMP() AND `breaker` IS NULL ORDER BY `id` DESC LIMIT 1");
$jailrow = $db->num_rows();
if ($jailrow >= 1) {
    $jt = $db->fetch_object();
    $jte = (($jt->timeleft - time()) >= 1) ? ($jt->timeleft - time()) : null;
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
    <li><a href="#flyplass.php">Flyplass</a></li>
    <li><a href="#Drap">Drap </a></li>
    <li><a href="#oppdrag.php">Oppdrag</li>
    <li><a href="#Ran">Ran </a></li>
</ul>
<h2>Verdier</h2>
<ul>
    <li><a href="#Marked">Svarteb&oslash;rsen </a></li>
    <li><a href="#Bunker">Bunker </a></li>
    <li><a href="bank.php">Banken </a></li>
    <li><a href="#Poeng">Poeng </a></li>
    <li><a href="#verving.php">Verving </a></li>
    <li><a href="#Auksjon">Auksjon </a></li>
    <li><a href="#Firmaer">Firmaer </a></li>
    <li><a href="#Garasje">Garasje </a></li>
</ul>
<h2>Kommunikasjon</h2>
<ul>
    <li><a href="innboks.php">Innboks (<span style="color:#ff0">jobbes med</span>)</a></li>
    <li><a href="#deputy.php">Send inn s&oslash;knad! </a></li>
    <li><a href="#support.php">Support </a></li>
    <li><a href="<?=$onl;?>">Spillere p&aring;logget</a> (<?=$ant;?>)</li>
    <li><a href="nyheter.php">Nyheter</a></li>
    <li><a href="Ledelsen">Ledelsen </a></li>
</ul>
<h2>Sosialt</h2>
<ul>
    <li><a href="chat.php">Chat</a><?php
        if ($num2 >= 2) {
            echo "($num2)";
        }
        ?></li>
    <li><a href="nyforum.php?type=1">Generelt Forum </a></li>
    <li><a href="nyforum.php?type=2">Salg og S&oslash;knadsforum </a></li>
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