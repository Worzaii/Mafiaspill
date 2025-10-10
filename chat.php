<?php
include("core.php");
startpage("Chat");
echo "<h1>Chat - Talk with other players</h1>";

if (r1() || r2() || r3()) {
    $dis = <<<END
<a href="tomprat.php" onclick="return confirm('Are you sure you want to empty the chat?');">Wipe chat</a>
END;
    $dis2 = <<<END
<a href="visprat.php">Show the entire chat history</a>
END;

    echo info($dis);
    /*echo info($dis2);*/
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
	<br><b>You've been shut out of the chat by $banner. You can only read.<br>
    You have </h3><span id="$uid"></span><script>teller($timeleft,"$uid","true","ned");</script> left of the write-out. If this might have been by mistake, reach out to support.<br><br>
	Reason for ban: $grunn</b></div><br>
HTML;
} else {
    echo <<<HTML
    <br>
    <input name="write" type="text" id="write" placeholder="Enter to post">
    <br>
HTML;
}
echo <<<HTML
    <div id="praten"></div>
    <script src="js/prat.js" type="text/javascript"></script>
HTML;
endpage();
