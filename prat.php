<?php
define("NOUPDATE", 1);
require_once './core.php';
header('Content-Type: text/html; charset=UTF-8');
$ikkevis = false;
if (isset($_GET['write'])) {
    if ($_GET['write'] == 'uptime' && r1()) {
        $uptime = shell_exec('uptime');
        $db->query("INSERT INTO `chat`(`uid`,`message`,`timestamp`) VALUES ('0','$uptime',UNIX_TIMESTAMP())");
    }
    if ($obj->health == 0) {
        $_SESSION['chatwarning'] = [
            'string' => feil('En d&oslash;d mann kan vell ikke snakke?'),
            'time' => (time()
                + 10)
        ];
    } else {
        $s = $db->prepare("SELECT count(*) FROM `forumban` WHERE `uid` = ? AND `bantime` > unix_timestamp() AND
                               `active` = '1' ORDER BY `bantime` DESC LIMIT 1");
        $s->execute([$obj->id]);
        if ($s->fetchColumn() == 1) {
            /* If banned from the forum, don't post messages to the chat */
            $_SESSION['chatwarning'] = [
                'string' => '<p style="color:#f00;">Du har ikke tillatelse til &aring; skrive, pr&oslash;v igjen senere.</p>',
                'time' => (time() + 10)
            ];
        } else {
            $w = $_GET['write'];
            if (strlen($w) <= 1) {
                $time = time() + 5;
                $_SESSION['chatwarning'] = [
                    'string' => '<p style="color:#f00;">Du m&aring; skrive 2 tegn eller mer for &aring; bruke chatten!</p>',
                    'time' => $time
                ];
            } else {
                $ins = $db->prepare("INSERT INTO `chat` VALUES(NULL,?,?,UNIX_TIMESTAMP())");
                $ins->execute([
                    $obj->id,
                    $w
                ]);
                header("Content-Type: application/json");
                if ($ins->rowCount() == 0) {
                    echo json_encode(['s' => 0]);
                    $ikkevis = true;
                } else {
                    echo json_encode(['s' => 1]);
                    $ikkevis = true;
                }
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
    while ($r = $chat->fetchObject()) {
        $message = smileys(htmlentities($r->message, ENT_NOQUOTES, 'UTF-8'));
        $message = wordwrap($message, 200, "<br>\n", true);
        $uob = user($r->uid, 1);
        if ($r->uid == 0) {
            $uob = "Systemet";
        } else {
            $uob = '<a href="profil.php?id=' . $uob->id . '">' . $uob->user . '</a>';
        }
        if ($r->id % 2) {
            echo
                '<div class="chat ct1"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        } else {
            echo
                '<div class="chat ct2"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        }
    }
}
