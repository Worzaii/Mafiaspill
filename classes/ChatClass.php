<?php

namespace Chats;

class ChatClass
{
    public $result;

    public function __construct()
    {
    }

    public function getChat($num_messages)
    {
        $this->result = $GLOBALS["db"]->query("select * from mafia.chat order by id desc limit 0,$num_messages");
        return true;
    }

    public function generateChatHeader()
    {
        $holder = "";
        if ($this->result !== null) {
            while ($r = mysqli_fetch_object($this->result)) {
                $message = smileys(htmlentities($r->message, ENT_NOQUOTES, 'UTF-8'));
                $message = wordwrap($message, 200, "<br />\n", true);
                $uob = user($r->uid, 1);
                if (!$uob) {
                    $uob = "Systemet";
                } else {
                    $uob = '<a href="profil.php?id=' . $uob->id . '">' . $uob->user . '</a>';
                }
                if ($r->id % 2) {
                    $holder .=
                        '<div class="ct1"><b>[' . date(
                            "H:i:s d.m.y",
                            $r->timestamp
                        ) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
                } else {
                    $holder .=
                        '<div class="ct2"><b>[' . date(
                            "H:i:s d.m.y",
                            $r->timestamp
                        ) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
                }
            }
        }
        return $holder;
    }
}
