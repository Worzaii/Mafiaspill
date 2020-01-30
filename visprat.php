<?php
include("core.php");
startpage("Viser hele prat databasen!");
if (!r1() && !r2() && !r3()) {
    echo '<h1>Ingen tilgang!</h1>';
    echo feil('Du har ikke tilgang hit!');
} else {
    echo '<h1>Viser hele prat!</h1>';
    $chat = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC");
    if (isset($_SESSION['chatwarning'])) {
        if (($_SESSION['chatwarning']['time'] - time()) >= 0) {
            echo $_SESSION['chatwarning']['string'];
        } else {
            unset($_SESSION['chatwarning']);
        }
    }
    if ($chat->num_rows >= 1) {
        while ($r = $chat->fetch_object()) {
            $teksten = htmlentities($r->message, ENT_NOQUOTES, 'UTF-8');
            if ($r->uid == 0) {
                $uob = "Systemet";
            } else {
                $uob = user($r->uid);
            }
            if ($r->id % 2) {
                echo
                    '<div class="chat ct1"><b>[' . date("H:i:s d.m.y", $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <br><span class="chattext">' . $teksten . '</span></div>';
            } else {
                echo
                    '<div class="chat ct2"><b>[' . date("H:i:s d.m.y", $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <br><span class="chattext">' . $teksten . '</span></div>';
            }
        }
    } else {
        echo info('Det var ingen meldinger i praten n&aring;');
    }
}
endpage();

