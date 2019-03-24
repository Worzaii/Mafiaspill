<?php
if (defined("LVL") && LVL == TRUE) {
    $dir = '../';
} else {
    $dir = "./";
}
global $obj;

function startpage($title = NAVN_DOMENE)
{
    global $dir;
    global $obj, $db;
    print '<!DOCTYPE html>
<html lang="no">
  <head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/style2.css">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.gif" type="image/gif">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>'.$title.'</title>
  <script src="/js/jquery.js"></script>
  <script src="js/teller.js"></script>
  <script src="js/loggteller.js"></script>
  ';
    $db->query("SELECT * FROM `jail` WHERE `timeleft` > UNIX_TIMESTAMP() AND `breaker` != NULL");
    $anyjail = ($db->num_rows() > 0) ? $db->num_rows() : NULL;

    $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800) AND UNIX_TIMESTAMP() ORDER BY `lastactive` DESC");
    $late_online = $db->num_rows(); /* Online last 30 minutes */
    $onl         = "online.php";
    print'
  </head>
  <body>
  <div id="repro">
    <div class="content">
      <nav>
        <ul>
          <li><a href="profil.php?id='.$obj->id.'">Profil</a></li>
          <li><a href="innboks.php">Innboks (in remake)</a></li>
          <li><a href="statistikk.php">Statistikk</a></li>
          <li><a href="Fengsel">Fengsel'.$anyjail.'</a></li>
          <li><a href="endreprofil.php">Endre Profil</a></li>
          <li><a href="'.$onl.'">Spillere p&aring;logget ('.$late_online.')</a></li>
        </ul>
      </nav>
    </div>
  </div>
  <header id="headerbg">
  <div id="header">';
    /* Chat on top of the page */
    global $db;
    $chat        = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC LIMIT 1");
    if ($db->num_rows() > 0) {
        while ($r = mysqli_fetch_object($chat)) {
            $teksten = htmlentities($r->mld, ENT_NOQUOTES, 'UTF-8');
            $uob     = user($r->uid, 1);
            if ($uob->status == 1) {
                $teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            } else if ($uob->status == 2 || $uob->status == 6) {
                $teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            } else {
                $teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            }
            $par = null;
            if ($r->id % 2) {
                echo
                '<div class="chat ct1"  style="width: 980px;padding: 5px 10px 5px 10px;font-size: 10px;color: #000;background: rgba(0, 0, 0, 0.66);margin-top: 0px;-moz-box-shadow: inset 0 0 10px #000000;-webkit-box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.86);"><a href="chat.php"><b>['.date("H:i:s",
                    $r->time).']</b> &lt;'.user($r->uid).'&gt;: <span class="chattext">'.$teksten.'</span></a></div>';
            } else {
                echo
                '<div class="chat ct2"  style="width: 980px;padding: 5px 10px 5px 10px;font-size: 10px;color: #000;background: rgba(0, 0, 0, 0.66);margin-top: 0px;-moz-box-shadow: inset 0 0 10px #000000;-webkit-box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.86);"><a href="chat.php"><b>['.date("H:i:s",
                    $r->time).']</b> &lt;'.user($r->uid).'&gt;: <span class="chattext">'.$teksten.'</span></a></div>';
            }
        }
    }

    /* Slutt p&aring; chat &oslash;verst */
    echo '
<noscript><p>&Aelig; spille mafia-no uten javascript aktivert vil vise seg &aring; v&aelig;re en ulempe for deg, vennligst aktiver javascript.</p></noscript>
  </div>
  </header>
  <section>
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

function endpage()
{
    print '
        </div>
        <div id="rightmenu">';
    include_once './inc/right.php';
}

function redirect($url, $wait)
{
    header("Refresh: $wait; url=$url");
}

function city($city, $way = 1)
{
    if (!is_numeric($city) || empty($city)) {
        $by = "ukjent";
    } else {
        $int = array(1, 2, 3, 4, 5, 6, 7, 8);
        $var = array("Oslo", "Bergen", "Trondheim", "Stavanger", "Fredrikstad", "Troms&oslash;", "Sarpsborg", "Lillestr&oslash;m"); //Norske byer ONLY :)
        if ($way == 1) {
            $by = str_replace($int, $var, $city);
        } else if ($way == 0) {
            $by = str_replace($var, $int, $city);
        }
    }
    return ($by);
}

/**
 *
 * @param type $i er IDen vi &oslash;nsker &aring; sjekke opp
 * @param type $obj (<b>0</b>|1) bestemmer om funksjonen skal returnere brukerobjektet om det finnes.
 * @return boolean or object
 */
function user($i, $obj = 0)
{
    global $dir;
    global $db;
    $s = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($i)."'");
    if ($db->num_rows() == 1) {
        if ($obj == 1) {
            return $db->fetch_object($s);
        }
        $obj = $db->fetch_object($s);
        $res = ($db->num_rows() == 1) ? '<a href="'.$dir.'profil.php?id='.$obj->id.'">'.$obj->user.'</a>' : 'Ingen';
        return($res);
    } else {
        return false;
    }
}

function bilde($i)
{
    global $dir;
    global $db;
    $s   = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($i)."'");
    $obj = $db->fetch_object($s);
    $res = ($db->num_rows() >= 1) ? $obj->image : $dir.'/imgs/nopic.png';
    return($res);
}

function ban($id)
{
    global $db;
    $db->query("SELECT * FROM `ban` WHERE `uid` = '$id' AND `active` = '1' ORDER BY `id` DESC");
    if ($db->num_rows() == 1) {
        $ac = $db->fetch_object();
        if ($ac->active == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function ipbanned($ip)
{
    global $db;
    $db->query("SELECT * FROM `ipban` WHERE `ip` = '".ip2long($ip)."' AND `active` = 1");
    if ($db->num_rows() == 1) {
        $query = $db->fetch_object();
        die("<p>'.$ip.' er blokkert fra dette stedet, grunnet:<br>'.$query->reason.'</p>");
    }
}

function timec($sec)
{
    /* Vise i minutter og sekunder */
    $res  = null;
    $min  = floor($sec / 60);
    $seks = floor($sec - ($min * 60)); //Resterende sekunder
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
    return($res);
}

function status($s, $t = 0)
{
    global $db;
    if ($t == 0) {
        $q = $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($s)."' LIMIT 0,1");
    } else if ($t == 1) {
        $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($s)."' LIMIT 0,1");
    } else if ($t == 2) {
        $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($s)."' LIMIT 0,1");
    }
    if ($db->num_rows() == 1) {
        $f     = $db->fetch_object();
        $int   = array(1, 2, 3, 4, 5, 6);
        /*
          ##1:Administratorstatus
          ##2:Moderatorstatus
          ##3:Forum Moderatorstatus
          ##4:Picmaker
          ##5:Vanlig spillerstatus
          ##6:D&oslash;d spillerstatus
         */
        $var   = array("<span class='stat1' title='Admin'>", "<span class='stat2' title='Moderator'>", "<span class='stat3' title='Forum moderator'>",
            NULL, "<span class='stat5' title='Vanlig spiller'>", "<span class='stat6' title='D&oslash;d'>");
        $names = array('Admin', 'Moderator', 'Forum Moderator', 'Picmaker', 'Vanlig spiller', 'D&oslash;d');
        $st    = str_replace($int, $var, $f->status);
        if ($t == 0) {
            return ($st.$s.'</span>');
        } else if ($t == 1) {
            if ($f->status == 4) {
                return '<span title="Picmaker">'.rainbow($f->user)."</span>";
            } else {
                return ($st.$f->user.'</span>');
            }
        } else if ($t == 2) {
            $ulvl = $f->status - 1;
            echo 'rrrrrrrrrrrrrrrrrrrrr    --()()()()((Her: '.$ulvl.',status:'.$f->status.'))-- rrrrrrrrrrrrr';
            return($names[$ulvl]);
        }
    } else {
        return "<em title='Det finnes ingen bruker med beskrivelsen ".$s."'>Ingen</em>";
    }
}

function user_exists($username, $ret = 0)
{
    global $db;
    $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($username)."'");
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
    $db->query("SELECT * FROM `firma` WHERE `id` = '".$db->escape($id)."'");
    if ($db->num_rows() == 1) {
        $f = $db->fetch_object();
        return array($f->Navn, $f->Eier, $f->Type, $f->Konto, $f->By);
    } else {
        return false;
    }
}

function liv_check()
{
    global $dir;
    global $obj;
    if ($obj->health <= 0) {
        return include($dir."death.php");
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
        include_once($dir."inc/desp.php");
    } else {
        return;
    }
}

function modkill_check()
{
    global $dir;
    global $obj;
    global $db;
    $s = $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id' AND `moddet` = '1'");
    if ($db->num_rows() >= 1) {
        return include($dir."moddet.php"); //Henter die fil
    } else {
        return;
    }
}

function fengsel($timereturn = NULL)
{
    global $dir;
    global $obj;
    global $db;
    $now = time();
    $s   = $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `breaker` = '0' AND `timeleft` > '$now' ORDER BY `id` DESC LIMIT 1");
    if ($timereturn == true) {
        $f = $db->fetch_object();
        return ($f->timeleft - $now);
    } else {
        if ($db->num_rows() == 0) {
            return false;
        } else {
            return true;
        }
    }
}

function bunker($tr = false)
{
    global $obj;
    global $db;
    $q = $db->query("SELECT * FROM `bunkerinv` WHERE `tid` = '".$obj->id."' AND `accepted` = '1' AND `timeleft` > ".time()." AND `used` = '1' AND `declined` = '0' AND `gone` = '0'");
    if ($db->num_rows() == 1) {
        if ($tr) {
            $g = $db->fetch_object();
            return $g->timeleft;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function settinn($uid, $res = "?", $timeleft = 90)
{
    global $db;
    $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($uid)."'");
    $time = time() + $timeleft;
    if ($db->num_rows() == 1) {
        if ($db->query("INSERT INTO `mafia_no_net`.`jail`(`time`,`uid`,`reason`,`timeleft`) VALUES('".time()."','".$db->escape($uid)."','".$db->escape($res)."','$time')")) {
            return true;
        } else {
            return $db->query_error();
        }
    } else {
        return false;
    }
    return false;
}

function bbcodes($text, $html = 1, $link1 = 1, $link2 = 1, $understrek = 1, $tykk = 1, $kursiv = 1, $midtstilt = 1,
                 $farge = 1, $bilde = 1, $storrelse = 1, $hr = 1, $linjeskift = 1, $smil = 1, $shadow = 0, $you = 0,
                 $decode = 0, $entit = 1)
{
    if ($html == 1) {
        if ($entit == 0) {
            $text = htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1');
        }
        if ($entit == 1) {
            $text = htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');
        }
    }
    if ($link1 == 1) {
        $text = preg_replace('#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\ text="(.+)"\]#',
            '<a href="htt$1$2" title="$1$2">$3</a>', $text);
    }
    if ($link2 == 1) {
        $text = preg_replace('#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\]#i',
            '<a href="htt$1$2">htt$1$2</a>', $text);
    }
    if ($understrek == 1) {
        $text = preg_replace("/\[u\](.*?)\[\/u\]/is", "<span style='text-decoration:underline;'>$1</span>", $text);
    }
    if ($tykk == 1) {
        $text = preg_replace("/\[b\](.*?)\[\/b\]/is", "<span style='font-weight:bold'>$1</span>", $text);
    }
    if ($kursiv == 1) {
        $text = preg_replace("/\[i\](.*?)\[\/i\]/is", "<span style='font-style:italic'>$1</span>", $text);
    }
    if ($midtstilt == 1) {
        $text = preg_replace("/\[c\](.*?)\[\/c\]/is", "<div style='text-align:center;'>$1</div>", $text);
    }
    if ($farge == 1) {
        $text = preg_replace("/\[f=#([0-9a-z]+)\](.*?)\[\/f\]/is", "<span style=color:#$1>$2</span>", $text);
    }
    if ($bilde == 1) {
        $text = preg_replace("#\[img=htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)]#",
            "<img src=\"htt$1$2\" style=\"max-width:100%;\" alt=\"\">", $text);
    }
    if ($storrelse == 1) {
        $text = preg_replace('#\[size=([0-9]+)\](.*?)\[/size\]#s', '<span style="font-size:$1px">$2</span>', $text);
    }
    if ($hr == 1) {
        $text = preg_replace("/(.*?)\[hr\](.*?)/is", "$1<hr>$2", $text);
    }
    if ($linjeskift == 1) {
        $text = str_replace("\n", "<br>", $text);
    }
    if ($smil == 1) {
        $text = str_replace(array(":)", ":D", ":P", ":-/", ";)", ":(", ":O", "&lt;3", ":S", ":*"),
            array('<img src="smileys/Content.png" alt=":)">', '<img src="smileys/Grin.png" alt=":D">', '<img src="smileys/Yuck.png" alt=":P">',
            '<img src="smileys/Slant.png" alt=":-/">', '<img src="smileys/Sarcastic.png" alt=";)">', '<img src="smileys/Frown.png" alt=":(">',
            '<img src="smileys/Gasp.png" alt=":O">', '<img src="smileys/Heart.png" alt="&lt;3">', '<img src="smileys/Confused.png" alt=":S">',
            '<img src="smileys/Kiss.png" alt=":*">'), $text);
    }
    if ($shadow == 1) {
        $text = preg_replace(array("/\[s1\](.*?)\[\/s1\]/is", "/\[s2 f=\"#(.*?)\"\](.*?)\[\/s2\]/is"),
            array("<span style=\"text-shadow:none;text-shadow: #000000 2px 2px 2px;\">$1</span>", "<span style=\"text-shadow:none;text-shadow: #$1 2px 2px 2px;\">$2</span>"),
            $text);
    }
    if ($you) {
        $text = preg_replace("/\[youtube=([a-z0-9-_]+)\?([0-1])\]/is",
            "<iframe width=\"560\" height=\"315\" src=\"http://www.youtube.com/embed/$1?autoplay=$2\" frameborder=\"0\" allowfullscreen></iframe>",
            $text);
    }
    $text = preg_replace(
        array("/\<3/ix", "/\[li\](.*?)\[\/li\]/is", "/\[ul\](.*?)\[\/ul\]/is", "/\[ol\](.*?)\[\/ol\]/is"),
        array("&heart;", "<li>$1</li>", "<ul>$1</ul>", "<ol>$1</ol>"), $text);
    if ($decode == 1) {
        $text = utf8_decode($text);
    }
    $text = str_replace(array("æ", "ø", "å"), array("&aelig;", "&oslash;", "&aring;"), $text);
    $text = preg_replace("#\[spotify=(.+)\]#is",
        "<iframe src=\"https://embed.spotify.com/?uri=$1\" width=\"300\" height=\"380\" frameborder=\"0\" allowtransparency=\"true\"></iframe>",
        $text);
    return ($text);
}

function rank($xp)
{
    $xp2 = $xp;
    if ($xp <= 50) {
        $ranknr = 1;
        $rankty = "Soldat"; //Ranknavn
        $maxxp  = 50;
    } else if ($xp > 50 && $xp < 100) {
        $xp     = $xp - 50;
        $ranknr = 2;
        $rankty = "Capo"; //Ranknavn
        $maxxp  = 50;
    } else if ($xp >= 100 && $xp < 150) {
        $xp     = $xp - 100;
        $ranknr = 3;
        $rankty = "Underboss"; //Ranknavn
        $maxxp  = 50;
    } else if ($xp >= 150 && $xp < 250) {
        $xp     = $xp - 150;
        $ranknr = 4;
        $rankty = "Boss"; //Ranknavn
        $maxxp  = 100;
    } else if ($xp >= 250 && $xp < 350) {
        $xp     = $xp - 250;
        $ranknr = 5;
        $rankty = "Consigliere"; //Ranknavn
        $maxxp  = 350;
    } else if ($xp >= 350 && $xp < 500) {
        $xp     = $xp - 350;
        $ranknr = 6;
        $rankty = "Don"; //Ranknavn
        $maxxp  = 500;
    } else if ($xp >= 500 && $xp < 700) {
        $xp     = $xp - 500;
        $ranknr = 7;
        $rankty = "Mafioso"; //Ranknavn
        $maxxp  = 700;
    } else if ($xp >= 700 && $xp < 950) {
        $xp     = $xp - 700;
        $ranknr = 8;
        $rankty = "Omerta"; //Ranknavn
        $maxxp  = 950;
    } else if ($xp >= 950 && $xp < 1250) {
        $xp     = $xp - 950;
        $ranknr = 9;
        $rankty = "Vendetta"; //Ranknavn
        $maxxp  = 1250;
    } else if ($xp >= 1250 && $xp < 1400) {
        $xp     = $xp - 1250;
        $ranknr = 10;
        $rankty = "Godfather"; //Ranknavn
        $maxxp  = 1400;
    } else if ($xp >= 1400 && $xp < 3000) {
        $xp     = $xp - 1400;
        $ranknr = 11;
        $rankty = "Legende"; //Ranknavn
        $maxxp  = 2600;
    } else if ($xp >= 3000) {
        $ranknr = 12;
        $rankty = "Legendarisk Don"; //Ranknavn
        $maxxp  = 3000;
        if (($xp / $maxxp) > 1) {
            $rankty .= " x".floor($xp / $maxxp);
        }
    }
    return array($ranknr, $rankty, $xp, $maxxp);
}

function get_user($in)
{
    global $db;
    $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($in)."'");
    if ($db->num_rows($q) == 1) {
        return $db->fetch_object($q);
    } else {
        return false;
    }
}

function r1()
{
    global $obj;
    if ($obj->status == 1) {
        return true;
    } else {
        return false;
    }
}

function r2()
{
    global $dir;
    global $obj;
    if ($obj->status == 2) {
        return true;
    } else {
        return false;
    }
}

function r3()
{
    global $dir;
    global $obj;
    if ($obj->status == 3) {
        return true;
    } else {
        return false;
    }
}

function types($a, $b = 0)
{
    global $dir;
    $c = array(0, 1, 2, 3); //Typer i tall
    $d = array("Om spillet", "Om funksjoner", "Feil i spillet", "Klage", "Forslag"); //Typer definert i tekst
    if ($b == 0) {
        $e = str_replace($c, $d, $a); //Bytter om
    } else if ($b == 1) {
        $e = str_replace($d, $c, $a); //Bytter om
    }
    return ($e);
}

function famidtoname($id, $link = 0)
{
    global $dir;
    global $db;
    $db->query("SELECT * FROM `familier` WHERE `id` = '$id'")or die(mysqli_error($db->connection_id));
    if ($db->num_rows() == 1) {
        $navn = $db->fetch_object();
        if ($link == 1) {
            return '<a href="familievis.php?fam='.$navn->Navn.'">'.$navn->Navn.'</a>';
        } else {
            return $navn->Navn;
        }
    } else {
        return false;
    }
}

function sysmel($til, $melding)
{
    global $dir;
    global $db;
    if (is_array($til)) {
        $q = "INSERT INTO `sysmail` VALUES";
        foreach ($til as $id) {
            $q .= "(NULL,'$id',UNIX_TIMESTAMP(),'0','".$db->slash($melding)."'),";
        }
        $q = substr($q, -1);
        $db->query($q);
    } else {
        $db->query("INSERT INTO `sysmail` VALUES(NULL,'$til','".time()."','0','".$db->slash($melding)."')");
    }
}

function famlogg($spiller, $hendelse)
{
    global $dir;
    global $db;
    global $obj;
    $db->query("INSERT INTO `familielogg` (`familie`,`hendelse`,`time`,`spiller`) VALUES ('$obj->family','$hendelse',UNIX_TIMESTAMP(),'$spiller')");
}

function noaccess()
{
    ?>
    <h1>Ingen tilgang!</h1>
    <p style="color:#000;">Du har ikke <b style="color: #f00;">TILGANG</b> til denne siden</p>
    <p>Dersom du mener du skal ha tilgang, kontakt en admin/moderator.</p>


    <?php
}

function rainbow($text)
{
    $ret        = '';
    $colors     = array(
        'ff0033',
        'ff0000',
        'ff3300',
        'ff6600',
        'ff9900',
        'ffcc00',
        'ffff00',
        'ccff00',
        '99ff00',
        '66ff00',
        '33ff00',
        '00ff00',
        '00ff33',
        '00ff66',
        '00ff99',
        '00ffcc',
        '00ffff',
        '00ccff',
        '0099ff',
        '0066ff',
        '0033ff',
        '0000ff',
        '3300ff',
        '6600ff',
        '9900ff',
        'cc00ff');
    $i          = 0;
    $textlength = strlen($text);
    while ($i <= $textlength) {
        foreach ($colors as $value) {
            if ($text[$i] != "") {
                $ret .= '<span style="color:#'.$value.';">'.$text[$i]."</span>";
            }
            $i++;
        }
    }
    return $ret;
}

function weapons($r)
{
    $w = array(0 => "ingen", 1 => "Colt 1911", 2 => ".44 Magnum", 3 => "Beretta 9mm", 4 => "M8A1", 5 => "DSR 50", 6 => "SVT-40",
        7 => "M4", 8 => "Ak 47", 9 => "M14");
    return $w[$r];
}

function weapon($r)
{
    $vapen = array(
        0 => array('navn' => "Ingen", 'pris' => 0, 'power' => 0),
        1 => array('navn' => "Colt", 'pris' => 84200, 'power' => 1),
        2 => array('navn' => "Glock 64", 'pris' => 147400, 'power' => 2),
        3 => array('navn' => "Dual Berettas", 'pris' => 294800, 'power' => 3),
        4 => array('navn' => "Desert Eagle", 'pris' => 874200, 'power' => 4),
        5 => array('navn' => "MP5", 'pris' => 1623000, 'power' => 5),
        6 => array('navn' => "PP Bizon", 'pris' => 4125000, 'power' => 6),
        7 => array('navn' => "P90", 'pris' => 8250000, 'power' => 7),
        8 => array('navn' => "AK-47", 'pris' => 16500000, 'power' => 8),
        9 => array('navn' => "M4A4", 'pris' => 33000000, 'power' => 9),
        10 => array('navn' => "Magnum Sniper Rifle", 'pris' => 66000000, 'power' => 10)
    );
    return $vapen[$r]['navn'];
}

function feil($t)
{
    echo'<p class="feil">'.$t.'</p>';
}

function lykket($t)
{
    echo '<p class="lykket">'.$t.'</p>';
}
