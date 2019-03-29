<?php
global $db, $obj, $set;
$sql         = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time() - 1800)."' AND '".time()."' ORDER BY `lastactive` DESC");
$ant         = $db->num_rows();
$sql3        = $db->query("SELECT * FROM `chat`");
$num2        = $db->num_rows();
$sql4        = $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
$ant2        = $db->num_rows();
$db->query("SELECT * FROM `krimlogg` WHERE `uid` = '$obj->id' AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `timestamp` DESC LIMIT 0,1");
if ($db->num_rows() == 1) {
    $kt  = $db->fetch_object();
    $ktl = (($kt->timewait - time()) >= 1) ? ($kt->timewait - time()) : null;
} else {
    $ktl = null;
}
$db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `time` > '".time()."' ORDER BY `id` DESC LIMIT 0,1");
$numrows = $db->num_rows();
if ($numrows >= 1) {
    $bt  = $db->fetch_object();
    $btl = (($bt->time - time()) >= 1) ? ($bt->time - time()) : null;
} else {
    $bt  = NULL;
    $btl = NULL;
}
$db->query("SELECT * FROM `rob_log` WHERE `uid` = '$obj->id' AND `timestamp` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1");
$ranrows = $db->num_rows();
if ($ranrows >= 1) {
    $rt  = $db->fetch_object();
    $rtl = (($rt->timestamp - time()) >= 1) ? ($rt->timestamp - time()) : null;
} else {
    $rt  = NULL;
    $rtl = NULL;
}
$db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `timeleft` > UNIX_TIMESTAMP() AND `breaker` IS NULL ORDER BY `id` DESC LIMIT 1");
$jailrow = $db->num_rows();
if ($jailrow >= 1) {
    $jt  = $db->fetch_object();
    $jte = (($jt->timeleft - time()) >= 1) ? ($jt->timeleft - time()) : null;
} else {
    $jt  = NULL;
    $jte = NULL;
}
if ($obj->airportwait > time()) {
    $fte    = $obj->airportwait - time();
    $flytid = 1;
} else {
    $flytid = NULL;
}
$onl = "online.php";
?>
<h2>Rank</h2>
<ul>
    <?php
    if ($ktl == NULL) {
        echo '<li><a href="krim.php">Kriminalitet</a>';
    } else {
        echo '<li><a href="krim.php">Kriminalitet</a> <span style="font-size:10px;" id="krimteller">'.$ktl.'</span><script>loggteller('.$ktl.',"krimteller",false,"ned");</script></li>';
    }
    if ($btl == NULL) {
        echo '<li><a href="biltyveri.php">Biltyveri (<span style="color:#FF0">Jobbes med</span>)</a>';
    } else {
        echo '<li><a href="biltyveri.php">Biltyveri (<span style="color:#FF0">Jobbes med</span>)</a> <span style="font-size:10px;" id="bilteller">'.$btl.'</span><script>loggteller('.$btl.',"bilteller",false,"ned");</script></li>';
    }
    if ($rtl == NULL) {
        echo '<li><a href="stjel.php">Ran Spiller(<span style="color:#f00">ikke klar</span>)</a>';
    } else {
        echo '<li><a href="stjel.php">Ran Spiller(<span style="color:#f00">ikke klar</span>)</a> <span style="font-size:10px;" id="ranteller">'.$rtl.'</span><script>loggteller('.$rtl.',"ranteller",false,"ned");</script></li>';
    }
    if ($jte == NULL) {
        echo '<li><a href="fengsel.php">Fengsel</a>';
        if ($ant2 >= 1) {
            echo "($ant2)";
        } echo '</li>';
    } else {
        echo '<li><a href="fengsel.php">Fengsel</a> <span style="font-size:10px;" id="jailteller">'.$jte.'</span><script>loggteller('.$jte.',"jailteller",false,"ned");</script></li>';
    }
    if ($flytid == NULL) {
        echo '<li><a href="flyplass.php">Flyplass(<span style="color:#f00">ikke klar</span>)</a></li>';
    } else {
        echo '<li><a href="flyplass.php">Flyplass(<span style="color:#f00">ikke klar</span>)</a> <span style="font-size:10px;" id="flyteller">'.$fte.'</span><script>loggteller('.$fte.',"flyteller",false,"ned");</script></li>';
    }
    ?>
    <li><a href="#Drap">Drap (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#oppdrag.php">Oppdrag (<span style="color:#f00">ikke klar</span></a>)</li>
    <li><a href="#Ran">Ran (<span style="color:#f00">ikke klar</span>)</a></li>
</ul>
<h2>Verdier</h2>
<ul>
    <li><a href="#Marked">Svarteb&oslash;rsen (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Bunker">Bunker (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Bank">Banken (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Poeng">Poeng (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#verving.php">Verving (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Auksjon">Auksjon (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Firmaer">Firmaer (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#Garasje">Garasje (<span style="color:#f00">ikke klar</span>)</a></li>
</ul>
<h2>Kommunikasjon</h2>
<ul>
    <li><a href="#Innboks">Innboks (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#deputy.php">Send inn s&oslash;knad! (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="#support.php">Support (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="<?= $onl; ?>">Spillere p&aring;logget</a> (<?= $ant; ?>)</li>
    <li><a href="nyheter.php">Nyheter</a></li>
    <li><a href="Ledelsen">Ledelsen (<span style="color:#f00">ikke klar</span>)</a></li>
</ul>
<h2>Sosialt</h2>
<ul>
    <li><a href="chat.php">Chat</a> <?php
        if ($num2 >= 2) {
            echo "($num2)";
        }
        ?></li>
    <li><a href="nyforum.php?type=1">Generelt Forum (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="nyforum.php?type=2">Salg og S&oslash;knadsforum (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="nyforum.php?type=3">Annet (<span style="color:#f00">ikke klar</span>)</a></li>
    <?php
    if ($obj->family != NULL) {
        echo '<li><a href="familiepanel.php?side=konfam">Gjengen (<span style="color:#f00">ikke klar</span>)</a></li>';
    } else {
        echo '<li><a href="Familie">Gjengene (<span style="color:#f00">ikke klar</span>)</a></li>';
    }
    ?>
</ul>
<h2>Gambling</h2>
<ul>
    <li><a href="Lotto">Lotto (<span style="color:#f00">ikke klar</span>)</a></li>
    <li><a href="Blackjack">Blackjack (<span style="color:#f00">ikke klar</span>)</a></li>
</ul>