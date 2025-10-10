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
<h2>The Climb</h2>
<ul>
    <?php
    if ($ktl == null) {
        echo '<li><a href="crime.php">Crime</a>';
    } else {
        echo '<li><a href="crime.php">Action 1</a> <span style="font-size:10px;" id="action1Counter">' . $ktl . '</span>
<script>teller(' . $ktl . ',"action1Counter",false,"ned");</script></li>';
    }
    if ($btl == null) {
        echo '<li><a href="carTheft.php">action2</a>';
    } else {
        echo '<li><a href="carTheft.php">action2</a> <span style="font-size:10px;" id="action2Counter">' . $btl . '</span>
<script>teller(' . $btl . ',"action2Counter",false,"ned");</script></li>';
    }
    ?>
    <li><a href="airport.php">Airport</a></li>
</ul>
<h2>Fortune</h2>
<ul>
    <li><a href="bank.php">Bank</a></li>
</ul>
<h2>Communication</h2>
<ul>
    <li><a href="innboks.php">Inbox</a></li>
    <li><a href="<?= $onl; ?>">Players online</a> (<?= $ant; ?>)</li>
    <li><a href="news.php">News</a></li>
    <li><a href="crew.php">Crew</a></li>
</ul>
<h2>Social</h2>
<ul>
    <li><a href="chat.php">Chat<?php
            if ($num2 >= 2) {
                echo "($num2)";
            }
            ?></a></li>
    <li><a href="nyforum.php?type=1">General Forum</a></li>
    <li><a href="nyforum.php?type=2">Sales Forum</a></li>
    <li><a href="nyforum.php?type=3">Off topic</a></li>
    <?php
    /*if ($obj->family != null) {
        echo '<li><a href="familiepanel.php?side=konfam">Gjengen </a></li>';
    } else {
        echo '<li><a href="Familie">Gjengene </a></li>';
    }*/
    ?>
</ul>
<h2>Gambling</h2>
<ul>
    <li><a href="Lotto">Lotto </a></li>
    <li><a href="bj.php">Blackjack </a></li>
</ul>
