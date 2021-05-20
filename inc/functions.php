<?php

/**
 * @param string $title Sets the title of the HTML document. Also starts the first part of the HTML page
 */
function startpage($title = NAVN_DOMENE)
{
    global $obj, $db;
    $jail = $db->query(
        "SELECT COUNT(*) as `numrows` FROM `jail` WHERE `timeleft` > UNIX_TIMESTAMP() AND `breaker` IS NULL"
    );
    $numrows = $jail->fetchColumn();
    $GLOBALS["stored_queries"]["jail"] = $numrows;
    $anyjail = ($numrows > 0) ? " (" . $numrows . ")" : null;
    $online = $db->query(
        "SELECT COUNT(*) as `numrows` FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800)
    AND UNIX_TIMESTAMP() ORDER BY `lastactive` DESC"
    );
    $late_online = $online->fetchColumn();
    $GLOBALS["stored_queries"]["online"] = $late_online;
    $chat = "";
    /*$chathead = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC LIMIT 0,3");
    while ($r = $chathead->fetchObject()) {
        $message = smileys(htmlentities($r->message, ENT_NOQUOTES, 'UTF-8'));
        $message = wordwrap($message, 200, "<br>\n", true);
        $uob = user($r->uid, 1);
        if (!$uob) {
            $uob = "Systemet";
        } else {
            $uob = '<a href="profil.php?id=' . $uob->id . '">' . $uob->user . '</a>';
        }
        if ($r->id % 2) {
            $chat .=
                '<div class="ct1"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        } else {
            $chat .=
                '<div class="ct2"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        }
    }*/
    include "start.php";
    include_once './inc/left.php';
    print <<<'HTML'
</div>
<div id="main">
HTML;
}

/**
 * @return string Writes out the rest of the WebPage
 */
function endpage()
{
    print '
        </div>
        <div id="rightmenu">';
    include_once './inc/right.php';
    $m = explode(" ", microtime());
    $end = $m[0] + $m[1];
    /*error_log("Page: " . $_SERVER["REQUEST_URI"] . " used " . round($end - $GLOBALS["start"],
            7) . " seconds to execute.");*/
}

/**
 * @param $city
 * @param int $way 1=intToCity, 2=CityToInt
 * @return string City or Int depending on choice
 */
function city($city, $way = 1)
{
    if (!is_numeric($city) || empty($city)) {
        $by = "ukjent";
    } else {
        $int = [1, 2, 3, 4, 5, 6, 7, 8];
        $var = [
            "Oslo",
            "Bergen",
            "Trondheim",
            "Stavanger",
            "Fredrikstad",
            "Tromsø",
            "Sarpsborg",
            "Lillestrøm"
        ]; //Norske byer ONLY :)
        if ($way == 1) {
            $by = str_replace($int, $var, $city);
        } elseif ($way == 0) {
            $by = str_replace($var, $int, $city);
        }
    }
    return ($by);
}

/**
 *
 * @param type $i er IDen vi ønsker å sjekke opp
 * @param type $obj (<b>0</b>|1) bestemmer om funksjonen skal returnere brukerobjektet om det finnes.
 *
 * @return boolean or object
 */
function user($i, $obj = 0)
{
    #error_log("Running user function with the following values: ID=$i, OBJ=$obj");
    global $db;
    $s = $db->prepare("SELECT count(*) FROM `users` WHERE `id` = ?");
    $s->execute([$i]);
    #error_log("Result of execution: " . $s->execute([$i]));
    $rows = $s->fetchColumn();
    #error_log("Number of rows found: $rows");
    if ($rows == 1) {
        #error_log("User found on ID, getting info:");
        $requery = $db->prepare("select * from users where id = ?");
        $requery->execute([$i]);
        $user = $requery->fetchObject();
        #error_log("User information extracted: \n" . var_export($user, true));
        if ($obj == 1) {
            return $user;
        }
        return '<a href="profil.php?id=' . $user->id . '">' . $user->user . '</a>';
    } else {
        #error_log(var_export($s, true));
        $dummy = new \UserObject\User();
        $dummy->setUsername("System");
        $dummy->setUserID(1);
        return $dummy;
    }
}

function bilde($i)
{
    global $db;
    $s = $db->query("SELECT * FROM `users` WHERE `id` = '" . $db->escape($i) . "'");
    $obj = $db->fetch_object($s);
    $res = ($db->num_rows() >= 1) ? $obj->image : '/images/nopic.png';
    return ($res);
}

function timec($sec)
{
    $res = null;
    $min = floor($sec / 60);
    $seks = floor($sec - ($min * 60));
    if ($min >= 1) {
        $res .= $min;
        if ($min >= 2) {
            $res .= ' minutter';
        } else {
            $res .= ' minutt';
        }
    }
    if ($seks >= 1) {
        if ($min >= 1) {
            $res .= " og ";
        }
        $res .= $seks;
        if ($seks >= 2) {
            $res .= ' sekunder';
        } else {
            $res .= ' sekund';
        }
    }
    return ($res);
}

function status($s)
{
    //error_log("Status function called, working... Value passed: \"" . $s . "\"");
    global $db;
    if (!is_string($s) && $s == 0) {
        return "System";
    }
    $pre = $db->prepare(
        "SELECT id,user,status,picmaker,health FROM `users` WHERE `user` = :val1 OR `id` = :val2 LIMIT 0,1"
    );
    $pre->bindParam(":val1", $s);
    $pre->bindParam(":val2", $s);
    error_log(
        "File: " . __FILE__ . ", function: " . __FUNCTION__ . ": Result of execution: " . (($pre->execute(
        )) ? "Successful!" : "Failed!!") . "on line: " . __LINE__
    );
    if ($user = $pre->fetchObject()) {
//        error_log("Status function called, values: " . var_export($user, true));
        if ($user->status == 1) {
            $span = "stat1"; /* Admin */
        } elseif ($user->status == 2) {
            $span = "stat2"; /* Moderator */
        } elseif ($user->status == 3) {
            $span = "stat3"; /* Forum moderator */
        } elseif ($user->status == 4 && $user->health > 1 && $user->picmaker != 1) {
            $span = "stat4"; /* Normal user */
        } elseif ($user->status == 4 && $user->picmaker == 1 && $user->health > 1) {
            $span = "statpic";
        } elseif ($user->status == 5) {
            $span = "statnpc";
        } else {
            $span = "";
        }
        return "<span class='$span'>$user->user</span>";
    } else {
        return "Ukjent bruker...";
    }
}

function getUser($username, $returntype = 0)
{
    global $db;
    $userexists = $db->prepare("SELECT count(*) FROM `users` WHERE `user` = ?");
    $userexists->execute([$username]);
    if ($userexists->fetchColumn() == 1) {
        if ($returntype == 0) {
            return true;
        } else {
            $userexists = $db->prepare("SELECT * FROM `users` WHERE `user` = ?");
            $userexists->execute([$username]);
            $f = $userexists->fetchObject();
            if ($returntype == 1) {
                $f = $db->fetch_object();
                return $f->id;
            }
            if ($returntype == 2) {
                return $db->fetch_object();
            }
        }
    }
    return false;
}

function firma($id)
{
    global $db;
    $db->query("SELECT * FROM `firms` WHERE `id` = '" . $db->escape($id) . "'");
    if ($db->num_rows() == 1) {
        $f = $db->fetch_object();
        return [$f->Navn, $f->Eier, $f->Type, $f->Konto, $f->By];
    } else {
        return false;
    }
}

function fengsel($timereturn = null)
{
    global $obj;
    global $db;
    $us = $db->prepare(
        "SELECT count(*) FROM `jail` WHERE `uid` = ? AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC LIMIT 1"
    );
    $us->execute([$obj->id]);
    if ($us->fetchColumn() == 1) {
        if ($timereturn == true) {
            $us2 = $db->prepare(
                "SELECT timeleft FROM `jail` WHERE `uid` = ? AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC LIMIT 1"
            );
            $us2->execute([$obj->id]);
            $res = $us2->fetchObject();
            return $res->timeleft - time();
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function bunker($tr = false)
{
    global $obj;
    global $db;
    $bu = $db->prepare(
        "SELECT count(*) FROM `bunkerinv` WHERE `tid` = ? AND `accepted` = '1' AND `timeleft` > unix_timestamp() AND `used` = '1' AND `declined` = '0' AND `gone` = '0'"
    );
    $bu->execute([$obj->id]);
    if ($bu->fetchColumn() == 1) {
        if ($tr) {
            $bu2 = $db->prepare(
                "SELECT timeleft FROM `bunkerinv` WHERE `tid` = ? AND `accepted` = '1' AND `timeleft` > unix_timestamp() AND `used` = '1' AND `declined` = '0' AND `gone` = '0'"
            );
            $bu2->execute([$obj->id]);
            return $bu2->fetchColumn();
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function settinn($uid, $res = "?", $timeleft = 60)
{
    global $db;
    $db->query("SELECT * FROM `users` WHERE `id` = '" . $db->escape($uid) . "'");
    if ($db->num_rows() == 1) {
        if ($db->query(
            "INSERT INTO `jail`(`timestamp`,`uid`,`reason`,`timeleft`,`priceout`) VALUES(UNIX_TIMESTAMP(),'" . $db->escape(
                $uid
            ) . "','" . $db->escape($res) . "','" . (time()
                + $timeleft) . "','1000000')"
        )) {
            return true;
        } else {
            return $db->query_error();
        }
    } else {
        return false;
    }
    return false;
}

/**
 * TODO: Might migrate this into the BBcodes class at a later date
 * @param $text
 * @return mixed
 */
function smileys($text)
{
    //    $text = str_replace(
    //        [":)", ":D", ":P", ":-/", ";)", ":(", ":O", "&lt;3", ":S", ":*"],
    //        [
    //            '<img src="smileys/Content.png" alt=":)">',
    //            '<img src="smileys/Grin.png" alt=":D">',
    //            '<img src="smileys/Yuck.png" alt=":P">',
    //            '<img src="smileys/Slant.png" alt=":-/">',
    //            '<img src="smileys/Sarcastic.png" alt=";)">',
    //            '<img src="smileys/Frown.png" alt=":(">',
    //            '<img src="smileys/Gasp.png" alt=":O">',
    //            '<img src="smileys/Heart.png" alt="&lt;3">',
    //            '<img src="smileys/Confused.png" alt=":S">',
    //            '<img src="smileys/Kiss.png" alt=":*">'
    //        ],
    //        $text
    //    );
    return $text;
}

function get_userobject($in)
{
    global $db;
    $q = $db->query("SELECT * FROM `users` WHERE `id` = '" . $db->escape($in) . "'");
    if ($db->num_rows($q) == 1) {
        return $db->fetch_object($q);
    } else {
        return false;
    }
}

function r1()
{
    global $obj;
    return ($obj->status == 1);
}

function r2()
{
    global $obj;
    return ($obj->status == 2);
}

function r3()
{
    global $obj;
    return ($obj->status == 3);
}

function supp()
{
    global $obj;
    return ($obj->support == 1);
}

function types($a, $b = 0)
{
    $c = [0, 1, 2, 3];
    $d = ["Om spillet", "Om funksjoner", "Feil i spillet", "Klage", "Forslag"];
    if ($b == 0) {
        return str_replace($c, $d, $a);
    } elseif ($b == 1) {
        return str_replace($d, $c, $a);
    }
}

function famidtoname($id, $link = 0)
{
    /**
     * TODO: Create table for families.
     */
    global $db;
    $db->query("SELECT * FROM `familier` WHERE `id` = '$id'") or die(mysqli_error($db->con));
    if ($db->num_rows() == 1) {
        $navn = $db->fetch_object();
        if ($link == 1) {
            return '<a href="familievis.php?fam=' . $navn->Navn . '">' . $navn->Navn . '</a>';
        } else {
            return $navn->Navn;
        }
    } else {
        return false;
    }
}

function sysmel($til, $melding)
{
    /**
     * Create table for system messages, might implement with inbox.php
     */
    global $db;
    if (is_array($til)) {
        $q = "INSERT INTO `sysmail` VALUES";
        foreach ($til as $id) {
            $q .= "(NULL,'$id',UNIX_TIMESTAMP(),'0','" . $db->slash($melding) . "'),";
        }
        $q = substr($q, -1);
        $db->query($q);
    } else {
        $db->query("INSERT INTO `sysmail` VALUES(NULL,'$til','" . time() . "','0','" . $db->slash($melding) . "')");
    }
}

function noaccess()
{
    echo <<<HTML
    <h1>Ingen tilgang!</h1>
    <p>Du har ikke <b style="color: #f00;">TILGANG</b> til denne siden</p>
    <p>Dersom du mener du skal ha tilgang, kontakt en admin/moderator eller send en henvendelse til support.</p>
HTML;
}

function weapon($r)
{
    $vapen = [
        0 => ['navn' => "Ingen", 'pris' => 0, 'power' => 0],
        1 => ['navn' => "Colt", 'pris' => 84200, 'power' => 1],
        2 => ['navn' => "Glock 64", 'pris' => 147400, 'power' => 2],
        3 => ['navn' => "Dual Berettas", 'pris' => 294800, 'power' => 3],
        4 => ['navn' => "Desert Eagle", 'pris' => 874200, 'power' => 4],
        5 => ['navn' => "MP5", 'pris' => 1623000, 'power' => 5],
        6 => ['navn' => "PP Bizon", 'pris' => 4125000, 'power' => 6],
        7 => ['navn' => "P90", 'pris' => 8250000, 'power' => 7],
        8 => ['navn' => "AK-47", 'pris' => 16500000, 'power' => 8],
        9 => ['navn' => "M4A4", 'pris' => 33000000, 'power' => 9],
        10 => ['navn' => "Magnum Sniper Rifle", 'pris' => 66000000, 'power' => 10]
    ];
    return $vapen[$r]['navn'];
}

function feil($t)
{
    return '<div class="boxshape"><p class="feil">' . $t . '</p></div>';
}

function lykket($t)
{
    return '<div class="boxshape"><p class="lykket">' . $t . '</p></div>';
}

function info($t)
{
    return '<div class="boxshape"><p class="info">' . $t . '</p></div>';
}

function warning($t)
{
    return '<div class="boxshape"><p class="warning">' . $t . '</p></div>';
}

function canUseFunction($jail, $bunker)
{
    $write = "";
    if ($jail === 1) {
        $fe = fengsel(true);
        if ($fe !== false) {
            $write .= feil(
                    'Du er i fengsel, gjenstående tid: <span id="fengsel">' . $fe . '</span>
            <br>Du er ute kl. ' . date("H:i:s d.m.Y", (time() + $fe))
                ) .
                '<script type="text/javascript">teller(' . $fe . ', "fengsel", false, \'ned\');</script>';
        }
    }
    if ($bunker === 1) {
        $bu = bunker(true);
        if ($bu !== false) {
            $write .= '
            <p class="feil">Du er i bunker, gjenstående tid:
            <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
            <script>
            teller(' . ($bu - time()) . ', "bunker", false, \'ned\');
            </script>
            ';
        }
    }
    if (!empty($write)) {
        return $write;
    } else {
        return false;
    }
}
