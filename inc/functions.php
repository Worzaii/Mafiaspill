<?php
/**
 * @param string $title Sets the title of the HTML document. Also starts the first part of the HTML page
 */
function startpage($title = NAVN_DOMENE)
{
    global $obj, $db;
    print '<!DOCTYPE html>
<html lang="no">
  <head>
  <link rel="stylesheet" type="text/css" href="css/style_old.css">
  <link rel="shortcut icon" href="./favicon.ico">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>' . $title . '</title>
  <script src="./js/jquery.js"></script>
  <script src="./js/teller.js"></script>
  <script src="./js/loggteller.js"></script>
  ';
    $jail = $db->query("SELECT COUNT(*) as `numrows` FROM `jail` WHERE `timeleft` > UNIX_TIMESTAMP() AND `breaker` IS NULL");
    $numrows = $jail->fetchColumn();
    $GLOBALS["stored_queries"]["jail"] = $numrows;
    $anyjail = ($numrows > 0) ? " (" . $numrows . ")" : null;
    $online = $db->query("SELECT COUNT(*) as `numrows` FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800)
    AND UNIX_TIMESTAMP() ORDER BY `lastactive` DESC");
    $late_online = $online->fetchColumn();
    $GLOBALS["stored_queries"]["online"] = $late_online;
    print'
  </head>
  <body>
  <section class="newsection"></section>
  <div id="navbar_top">
    <div class="content">
      <nav>
        <ul>
          <li><a href="profil.php?id=' . $obj->id . '">Profil</a></li>
          <li><a href="innboks.php">Innboks</a></li>
          <li><a href="nyheter.php">Nyheter</a></li>
          <li><a href="fengsel.php">Fengsel' . $anyjail . '</a></li>
          <li><a href="bj.php">BlackJack</a></li>
          <li><a href="online.php">Spillere p&aring;logget (' . $late_online . ')</a></li>
        </ul>
      </nav>
    </div>
  </div>
  <div id="information">
            <p>Spillet har blitt oppdatert. CTRL + F5</p>
  </div>
  <!--<header id="headerbg">
  <div id="header"><div id="ct">';
    $chathead = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC LIMIT 0,3");
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
            echo
                '<div class="ct1"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        } else {
            echo
                '<div class="ct2"><b>[' . date("H:i:s d.m.y",
                    $r->timestamp) . ']</b> &lt;' . $uob . '&gt;: <span class="chattext">' . $message . '</span></div>';
        }
    }
    echo '</div>
<noscript><p>&Aring; spille ' . NAVN_DOMENE . ' uten javascript aktivert vil vise seg &aring; v&aelig;re fungere d&aring;rlig, vennligst aktiver javascript eller bruk en nettleser som st&oslash;tter dette.</p></noscript>
  </div>
  </header>-->
  <section style="top: 56px;position: relative;">
  <div class="wrapper">
  <div id="content">
  <div id="shadow">
  </div>
  <div id="leftmenu">';
    include_once './inc/left.php';
    print '
</div>
<div id="main">
';
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
    error_log("Page: " . $_SERVER["REQUEST_URI"] . " used " . round($end - $GLOBALS["start"],
            7) . " seconds to execute.");
    /*error_log("Number of queries on " . $_SERVER["REQUEST_URI"] . ": " . $GLOBALS["db"]->num_queries);*/
    /* This is no longer valid because I haven't implemented PDO as some other class or function than the core itself */
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
            "Troms&oslash;",
            "Sarpsborg",
            "Lillestr&oslash;m"
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
 * @param type $i er IDen vi &oslash;nsker &aring; sjekke opp
 * @param type $obj (<b>0</b>|1) bestemmer om funksjonen skal returnere brukerobjektet om det finnes.
 *
 * @return boolean or object
 */
function user($i, $obj = 0)
{
    global $db;
    $s = $db->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $s->execute([$i]);
    if ($user = $s->fetchObject()) {
        if ($obj == 1) {
            return $user;
        }
        return '<a href="profil.php?id=' . $user->id . '">' . $user->user . '</a>';
    } else {
        return false;
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

function ipbanned($ip)
{
    global $db;
    $st = $db->prepare("SELECT `reason` FROM `ipban` WHERE `ip` = ? AND `active` = 1 ORDER BY `id` DESC LIMIT 1");
    $st->execute([ip2long($ip)]);
    if ($reason = $st->fetchColumn()) {
        die('<p>' . $ip . ' er blokkert fra dette stedet, grunnet:<br>' . $reason . '</p>');
    }
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
    $pre = $db->prepare("SELECT id,user,status,picmaker,health FROM `users` WHERE `user` = :val1 OR `id` = :val2 LIMIT 0,1");
    $pre->bindParam(":val1", $s);
    $pre->bindParam(":val2", $s);
    error_log("Result of execution: " . (($pre->execute()) ? "Successful!" : "Failed!!"));
    if ($user = $pre->fetchObject()) {
        error_log("Status function called, values: " . var_export($user, true));
        if ($user->status == 1) {
            $span = "stat1";
        } elseif ($user->status == 2) {
            $span = "stat2";
        } elseif ($user->status == 3) {
            $span = "stat3";
        } elseif ($user->status == 4 || $user->status == 5 && $user->picmaker == 1 && $user->health > 1) {
            $span = "stat4";
        } elseif ($user->status == 4) {
            $span = "stat5";
        } else {
            $span = "";
        }
        return "<span class='$span'>$user->user</span>";
    } else {
        return "Ukjent bruker...";
    }
}

function user_exists($username, $ret = 0)
{
    global $db;
    $db->query("SELECT * FROM `users` WHERE `user` = '" . $db->escape($username) . "'");
    if ($db->num_rows() == 1) {
        if ($ret == 0) {
            return true;
        }
        if ($ret == 1) {
            $f = $db->fetch_object();
            return $f->id;
        }
        if ($ret == 2) {
            return $db->fetch_object();
        }
    } else {
        return false;
    }
}

function firma($id)
{
    global $db;
    $db->query("SELECT * FROM `firma` WHERE `id` = '" . $db->escape($id) . "'");
    if ($db->num_rows() == 1) {
        $f = $db->fetch_object();
        return [$f->Navn, $f->Eier, $f->Type, $f->Konto, $f->By];
    } else {
        return false;
    }
}

function liv_check()
{
    global $dir;
    global $obj;
    if ($obj->health <= 0) {
        return include($dir . "death.php");
    } else {
        return;
    }
}

function aktiv()
{
    global $dir;
    global $obj;
    global $db;
    $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id' AND `activated` = '0'");
    if ($db->num_rows() == 1) {
        include_once($dir . "inc/desp.php");
    } else {
        return;
    }
}

function fengsel($timereturn = null)
{
    global $obj;
    global $db;
    $us = $db->prepare("SELECT count(*) FROM `jail` WHERE `uid` = ? AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC LIMIT 1");
    $us->execute([$obj->id]);
    if ($us->fetchColumn() == 1) {
        if ($timereturn == true) {
            $us2 = $db->prepare("SELECT timeleft FROM `jail` WHERE `uid` = ? AND `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC LIMIT 1");
            $us2->execute([$obj->id]);
            return $us2->fetchObject()->timeleft - time();
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
    $bu = $db->prepare("SELECT count(*) FROM `bunkerinv` WHERE `tid` = ? AND `accepted` = '1' AND `timeleft` > unix_timestamp() AND `used` = '1' AND `declined` = '0' AND `gone` = '0'");
    $bu->execute([$obj->id]);
    if ($bu->fetchColumn() == 1) {
        if ($tr) {
            $bu2 = $db->prepare("SELECT timeleft FROM `bunkerinv` WHERE `tid` = ? AND `accepted` = '1' AND `timeleft` > unix_timestamp() AND `used` = '1' AND `declined` = '0' AND `gone` = '0'");
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
        if ($db->query("INSERT INTO `jail`(`timestamp`,`uid`,`reason`,`timeleft`,`priceout`) VALUES(UNIX_TIMESTAMP(),'" . $db->escape($uid) . "','" . $db->escape($res) . "','" . (time()
                + $timeleft) . "','1000000')")) {
            return true;
        } else {
            return $db->query_error();
        }
    } else {
        return false;
    }
    return false;
}

function bbcodes(
    $text,
    $html = 1,
    $link1 = 1,
    $link2 = 1,
    $understrek = 1,
    $tykk = 1,
    $kursiv = 1,
    $midtstilt = 1,
    $farge = 1,
    $bilde = 1,
    $storrelse = 1,
    $hr = 1,
    $linjeskift = 1,
    $smil = 1,
    $shadow = 0,
    $you = 0,
    $decode = 0,
    $entit = 1
) {
    if ($html == 1) {
        if ($entit == 0) {
            $text = htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1');
        }
        if ($entit == 1) {
            $text = htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');
        }
    }
    if ($link1 == 1) {
        $text = preg_replace(
            '#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$&()\'*+,;=%]+)" text="(.+)"\]#',
            '<a href="htt$1$2" title="$1$2">$3</a>',
            $text
        );
    }
    if ($link2 == 1) {
        $text = preg_replace(
            '#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\]#i',
            '<a href="htt$1$2">htt$1$2</a>',
            $text
        );
    }
    if ($understrek == 1) {
        $text = preg_replace("/\[u\](.*?)\[\/u\]/is",
            "<span style='text-decoration:underline;'>$1</span>", $text);
    }
    if ($tykk == 1) {
        $text = preg_replace("/\[b\](.*?)\[\/b\]/is", "<span style='font-weight:bold'>$1</span>",
            $text);
    }
    if ($kursiv == 1) {
        $text = preg_replace("/\[i\](.*?)\[\/i\]/is", "<span style='font-style:italic'>$1</span>",
            $text);
    }
    if ($midtstilt == 1) {
        $text = preg_replace("/\[c\](.*?)\[\/c\]/is", "<div style='text-align:center;'>$1</div>",
            $text);
    }
    if ($farge == 1) {
        $text = preg_replace("/\[f=#(([0-9a-f]){3}|([0-9a-f]){6})\](.*?)\[\/f\]/is",
            "<span style=\"color:#$1\">$4</span>", $text);
    }
    if ($bilde == 1) {
        $text = preg_replace(
            "#\[img=htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)]#",
            "<img src=\"htt$1$2\" style=\"max-width:100%;\" alt=\"\">",
            $text
        );
    }
    if ($storrelse == 1) {
        $text = preg_replace('#\[size=([0-9]+)\](.*?)\[/size\]#s',
            '<span style="font-size:$1px">$2</span>', $text);
    }
    if ($hr == 1) {
        $text = preg_replace("/(.*?)\[hr\](.*?)/is", "$1<hr>$2", $text);
    }
    if ($linjeskift == 1) {
        $text = str_replace("\n", "<br>", $text);
    }
    if ($smil == 1) {
        $text = str_replace(
            [":)", ":D", ":P", ":-/", ";)", ":(", ":O", "<3", ":S", ":*"],
            [
                '<img src="smileys/Content.png" alt=":)">',
                '<img src="smileys/Grin.png" alt=":D">',
                '<img src="smileys/Yuck.png" alt=":P">',
                '<img src="smileys/Slant.png" alt=":-/">',
                '<img src="smileys/Sarcastic.png" alt=";)">',
                '<img src="smileys/Frown.png" alt=":(">',
                '<img src="smileys/Gasp.png" alt=":O">',
                '<img src="smileys/Heart.png" alt="&lt;3">',
                '<img src="smileys/Confused.png" alt=":S">',
                '<img src="smileys/Kiss.png" alt=":*">'
            ],
            $text
        );
    }
    if ($shadow == 1) {
        $text = preg_replace(
            ["/\[s1\](.*?)\[\/s1\]/is", "/\[s2 f=\"#(.*?)\"\](.*?)\[\/s2\]/is"],
            [
                "<span style=\"text-shadow:none;text-shadow: #000000 2px 2px 2px;\">$1</span>",
                "<span style=\"text-shadow:none;text-shadow: #$1 2px 2px 2px;\">$2</span>"
            ],
            $text
        );
    }
    if ($you) {
        $text = preg_replace(
            "/\[youtube=([a-z0-9-_]+)\?([0-1])\]/is",
            "<iframe width=\"560\" height=\"315\" src=\"http://www.youtube.com/embed/$1?autoplay=$2\" frameborder=\"0\" allowfullscreen></iframe>",
            $text
        );
    }
    $text = preg_replace(
        [
            "/\<3/ix",
            "/\[li\](.*?)\[\/li\]/is",
            "/\[ul\](.*?)\[\/ul\]/is",
            "/\[ol\](.*?)\[\/ol\]/is"
        ],
        ["&heart;", "<li>$1</li>", "<ul>$1</ul>", "<ol>$1</ol>"],
        $text
    );
    if ($decode == 1) {
        $text = utf8_decode($text);
    }
    $text = str_replace(["æ", "ø", "å"], ["&aelig;", "&oslash;", "&aring;"], $text);
    $text = preg_replace(
        "#\[spotify=(.+)\]#is",
        "<iframe src=\"https://embed.spotify.com/?uri=$1\" width=\"300\" height=\"380\" frameborder=\"0\" allowtransparency=\"true\"></iframe>",
        $text
    );
    return ($text);
}

function smileys($text)
{
    $text = str_replace(
        [":)", ":D", ":P", ":-/", ";)", ":(", ":O", "&lt;3", ":S", ":*"],
        [
            '<img src="smileys/Content.png" alt=":)">',
            '<img src="smileys/Grin.png" alt=":D">',
            '<img src="smileys/Yuck.png" alt=":P">',
            '<img src="smileys/Slant.png" alt=":-/">',
            '<img src="smileys/Sarcastic.png" alt=";)">',
            '<img src="smileys/Frown.png" alt=":(">',
            '<img src="smileys/Gasp.png" alt=":O">',
            '<img src="smileys/Heart.png" alt="&lt;3">',
            '<img src="smileys/Confused.png" alt=":S">',
            '<img src="smileys/Kiss.png" alt=":*">'
        ],
        $text
    );
    return $text;
}

function rank($xp)
{
    if ($xp <= 50) {
        $ranknr = 1;
        $rankty = "Soldat";
        $maxxp = 50;
    } elseif ($xp > 50 && $xp < 100) {
        $xp = $xp - 50;
        $ranknr = 2;
        $rankty = "Capo";
        $maxxp = 50;
    } elseif ($xp >= 100 && $xp < 150) {
        $xp = $xp - 100;
        $ranknr = 3;
        $rankty = "Underboss";
        $maxxp = 50;
    } elseif ($xp >= 150 && $xp < 250) {
        $xp = $xp - 150;
        $ranknr = 4;
        $rankty = "Boss";
        $maxxp = 100;
    } elseif ($xp >= 250 && $xp < 350) {
        $xp = $xp - 250;
        $ranknr = 5;
        $rankty = "Consigliere";
        $maxxp = 350;
    } elseif ($xp >= 350 && $xp < 500) {
        $xp = $xp - 350;
        $ranknr = 6;
        $rankty = "Don";
        $maxxp = 500;
    } elseif ($xp >= 500 && $xp < 700) {
        $xp = $xp - 500;
        $ranknr = 7;
        $rankty = "Mafioso";
        $maxxp = 700;
    } elseif ($xp >= 700 && $xp < 950) {
        $xp = $xp - 700;
        $ranknr = 8;
        $rankty = "Omerta";
        $maxxp = 950;
    } elseif ($xp >= 950 && $xp < 1250) {
        $xp = $xp - 950;
        $ranknr = 9;
        $rankty = "Vendetta";
        $maxxp = 1250;
    } elseif ($xp >= 1250 && $xp < 1400) {
        $xp = $xp - 1250;
        $ranknr = 10;
        $rankty = "Godfather";
        $maxxp = 1400;
    } elseif ($xp >= 1400 && $xp < 3000) {
        $xp = $xp - 1400;
        $ranknr = 11;
        $rankty = "Legende";
        $maxxp = 2600;
    } elseif ($xp >= 3000) {
        $ranknr = 12;
        $rankty = "Legendarisk Don";
        $maxxp = 3000;
        if (($xp / $maxxp) > 1) {
            $rankty .= " x" . floor($xp / $maxxp);
        }
    }
    return [$ranknr, $rankty, $xp, $maxxp];
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

function support()
{
    global $obj;
    return ($obj->support == 1);
}

function types($a, $b = 0)
{
    $c = [0, 1, 2, 3];
    $d = ["Om spillet", "Om funksjoner", "Feil i spillet", "Klage", "Forslag"];
    if ($b == 0) {
        $e = str_replace($c, $d, $a); //Bytter om
    } elseif ($b == 1) {
        $e = str_replace($d, $c, $a); //Bytter om
    }
    return ($e);
}

function famidtoname($id, $link = 0)
{
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
    echo <<<'NOAC'
    <h1>Ingen tilgang!</h1>
    <p>Du har ikke <b style="color: #f00;">TILGANG</b> til denne siden</p>
    <p>Dersom du mener du skal ha tilgang, kontakt en admin/moderator eller send en henvendelse til support.</p>
NOAC;
}

function weapons($r)
{
    $w = [
        0 => "ingen",
        1 => "Colt 1911",
        2 => ".44 Magnum",
        3 => "Beretta 9mm",
        4 => "M8A1",
        5 => "DSR 50",
        6 => "SVT-40",
        7 => "M4",
        8 => "Ak 47",
        9 => "M14"
    ];
    return $w[$r];
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
