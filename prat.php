<?php
define("NOUPDATE", 1);
require_once './core.php';
header('Content-Type: text/html; charset=UTF-8');
$ikkevis = false;
if (isset($_GET['write'])) {
    if ($_GET['write'] == 'uptime' && r1()) {
        $uptime = system(uptime);
        $db->query("INSERT INTO `chat`(`uid`,`message`,`timestamp`) VALUES ('0','$uptime',UNIX_TIMESTAMP())");
    }
    if ($obj->health == 0) {
        /* Don't allow posting messages while dead, show a message for a few seconds */
        $_SESSION['chatwarning'] = ['string' => '<p style="color:#f00;">En d&oslash;d mann kan vell ikke snakke?</p>', 'time' => (time()
            + 10)];
    } else {
        $time = time();
        $s    = $db->query("SELECT * FROM `forumban` WHERE `uid` = '$obj->id' AND `bantime` > '$time' AND `act` = '1' ORDER BY `bantime` DESC LIMIT 1");
        if ($db->num_rows($s) == 1) {
            /* If banned from the forum, don't post messages to the chat */
            $_SESSION['chatwarning'] = ['string' => '<p style="color:#f00;">Du har ikke tillatelse til &aring; skrive, pr&oslash;v igjen senere.</p>',
                'time' => (time() + 10)];
        } else {
            $w = $db->escape($_GET['write']);
            if (strlen($w) <= 1) {
                $time                    = time() + 5;
                $_SESSION['chatwarning'] = ['string' => '<p style="color:#f00;">Du m&aring; skrive 2 tegn eller mer for &aring; bruke chatten!</p>',
                    'time' => $time];
            } else {
                $db->query("INSERT INTO `chat` VALUES(NULL,'$obj->id','$w',UNIX_TIMESTAMP())");
                header("Content-Type: application/json");
                echo json_encode(['s' => 1]);
                $ikkevis = true;
            }
        }
    }
}
if ($ikkevis == false) {
    $chat = $db->query("SELECT * FROM `chat` ORDER BY `timestamp` DESC LIMIT 0,30");
    if (isset($_SESSION['chatwarning'])) {
        if (($_SESSION['chatwarning']['time'] - time()) >= 0) {
            echo $_SESSION['chatwarning']['string'];
        } else {
            unset($_SESSION['chatwarning']);
        }
    }
    while ($r = mysqli_fetch_object($chat)) {
        $message = htmlentities($r->message, ENT_NOQUOTES, 'UTF-8');
        $uob     = user($r->uid, 1);
        /* Considering removing text related coding from chat to keep it pure */
        /* if ($uob->status == 1) {
          $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
          } else if ($uob->status == 2 || $uob->status == 6) {
          $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
          } else {
          $teksten = bbcodes($teksten, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0);
          } */
        if ($r->uid == 0) {
            $uob = "Systemet";
        } else {
            $uob = '<a href="profil.php?id='.$uob->id.'">'.$uob->user.'</a>';
        }
        if ($r->id % 2) {
            echo
            '<div class="chat ct1"><b>['.date("H:i:s d.m.y", $r->timestamp).']</b> &lt;'.$uob.'&gt;: <span class="chattext">'.$message.'</span></div>';
        } else {
            echo
            '<div class="chat ct2"><b>['.date("H:i:s d.m.y", $r->timestamp).']</b> &lt;'.$uob.'&gt;: <span class="chattext">'.$message.'</span></div>';
        }
    }
}
