<?php
if(defined("LVL") && LVL == TRUE){
  $dir = '../';
}
else {$dir = "./";}
  $ex = NULL;
  $ad = NULL;
  global $obj;
  //$styl=' style="display:none;"';
  $js   ='<script type="text/javascript" src="js/au.js?'.time().'"></script>';
function startpage($title = "Uten tittel",$style = NULL){
 global $dir;
  global $ad,$ex,$js,$obj,$db;
  $rid = safegen($obj->user, $obj->pass)."ekstra_salt";
print '<!DOCTYPE html>
<html lang="no">
  <head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/style2.css?'.time().'">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.gif" type="image/gif">
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <title>'.$title.'</title>
  <script type="text/javascript" src="/js/jquery.js"></script>
  <script type="text/javascript">
  var uniqid = \''.$rid.'\';
  </script>
  <script type="text/javascript" src="js/teller.js"></script>
  <script type="text/javascript" src="js/loggteller.js"></script>
  '.$js;
echo $ad;
if($style != NULL){
  print $style;
}
$db->query("SELECT * FROM `jail` WHERE `timeleft` > UNIX_TIMESTAMP() AND `breaker` = '0' AND `bryttut` = '0'");
if($db->num_rows() >= 1){
  $anyjail = ($db->num_rows() == 1) ? " (1)" : " (".$db->num_rows().")";
}
else{
  $anyjail=NULL;
}
$db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time() - 1800)."' AND UNIX_TIMESTAMP() ORDER BY `lastactive` DESC");
$num = $db->num_rows();
$sql5 = $db->query("SELECT * FROM `mail2` WHERE `tid` = '$obj->id' AND `read` = '0' ORDER BY `id` DESC");
  $nymeldinger = $db->num_rows();
  $sqlsys=$db->query("SELECT * FROM `sysmail` WHERE `uid` = '{$obj->id}' AND `read` = '0'");
  $ex = $db->num_rows();
  $nymeldinger = $nymeldinger + $ex;/*Legger systemmeldinger sammen med innboks.*/
  if($nymeldinger == 0){
      $nymeldinger = null;
  }
  else{
      if($nymeldinger >= 2)$ee="e";
      if($nymeldinger == 1)$ee=null;
      $nymeldinger = ' (<b>'.number_format($nymeldinger).' ny'.$ee.'</b>)';
  }
  $lib = unserialize($obj->settings);
  $onl = ($lib["o"] == 1) ? "/Online" : "online.php";
print'
  </head>
  <body>
'.$ex.'
  <!--<div id="repro">
    <div class="content">
      <nav>
        <ul>
          <li><a href="profil.php?id='.$obj->id.'">Profil</a></li>
          <li><a href="innboks.php">Innboks'.$nymeldinger.'</a></li>
          <li><a href="statistikk.php">Statistikk</a></li>
          <li><a href="Fengsel">Fengsel'.$anyjail.'</a></li>
          <li><a href="endreprofil.php">Endre Profil</a></li>
          <li><a href="'.$onl.'">Spillere pålogget ('.$num.')</a></li>
        </ul>
      </nav>
    </div>
  </div>-->
  <header id="headerbg">
  <div id="header">';
/*Chat øverst på siden*/
global $db;
$chat = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC LIMIT 1")or die(mysqli_error($db->connection_id));
while($r = mysqli_fetch_object($chat)){
    $teksten = $r->mld;
    $teksten = htmlentities($teksten, ENT_NOQUOTES, 'UTF-8');
    $uob = user($r->uid,1);
    if($uob->status == 1){
      $teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    }
    else if($uob->status == 2 || $uob->status == 6){
      $teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    }
    else{$teksten = bbcodes($teksten, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);}
    $par = null;
    if($r->id % 2){
                    echo
         '<div class="chat ct1"  style="width: 980px;padding: 5px 10px 5px 10px;font-size: 10px;color: #000;background: rgba(0, 0, 0, 0.66);margin-top: 0px;-moz-box-shadow: inset 0 0 10px #000000;-webkit-box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.86);"><a href="chat.php"><b>['.date("H:i:s",$r->time).']</b> &lt;'.user($r->uid).'&gt;: <span class="chattext">'.$teksten.'</span></a></div>';
    }
    else{
        echo
         '<div class="chat ct2"  style="width: 980px;padding: 5px 10px 5px 10px;font-size: 10px;color: #000;background: rgba(0, 0, 0, 0.66);margin-top: 0px;-moz-box-shadow: inset 0 0 10px #000000;-webkit-box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.86);"><a href="chat.php"><b>['.date("H:i:s",$r->time).']</b> &lt;'.user($r->uid).'&gt;: <span class="chattext">'.$teksten.'</span></a></div>';
    }
}
/*Slutt på chat øverst*/
echo '
<noscript>Å spille mafia-no uten javascript aktivert vil vise seg å være en ulempe for deg, vennligst aktiver javascript.</noscript></div>
  </header>
  <section>
  <div class="wrapper">
  <div id="content">
  <div id="shadow">
  </div>
  <div id="leftmenu">';
  include($dir."inc/left.php");
print '
</div>
<div id="main">
';
}

    function endpage(){
 global $dir;
        print '
        </div>
        <div id="rightmenu">';
        include($dir."inc/right.php");
    }
    function redirect($url, $wait){
 global $dir;
      header("Refresh: $wait; url=$url");
    }
    function city($city, $way = 1){
 global $dir;
        if(!is_numeric($city) || empty($city)){
          $by = "ukjent";
        }
        else{
          $int = array(1,2,3,4,5,6,7,8);
          $var = array("Oslo","Bergen","Trondheim","Stavanger","Fredrikstad","Troms&oslash;","Sarpsborg","Lillestr&oslash;m");//Norske byer ONLY :)
          if($way == 1){
            $by = str_replace($int,$var,$city);
          }
          else if($way == 0){
            $by = str_replace($var,$int,$city);
          }
        }
        return ($by);
    }
    /**
     *
     * @param type $i er IDen vi ønsker å sjekke opp
     * @param type $obj (<b>0</b>|1) bestemmer om funksjonen skal returnere brukerobjektet om det finnes.
     * @return boolean or object
     */
    function user($i,$obj=0){
      global $dir;
      global $db;
      $i = $db->escape($i);
      $s = $db->query("SELECT * FROM `users` WHERE `id` = '$i'");
      if($db->num_rows() == 1){
        if($obj==1)return $db->fetch_object($s);
          $obj = $db->fetch_object($s);
          $res = ($db->num_rows() >= 1) ? '<a class="user_menu" value="'.$obj->user.';'.$obj->id.'" href="'.$dir.'profil.php?id='.$obj->id.'">'.$obj->user.'</a>' : 'Ingen';
        return($res);
      }
      else{
        return false;
      }
    }
    function bilde($i){
      global $dir;
			global $db;
        $i = $db->escape($i);
        $s = $db->query("SELECT * FROM `users` WHERE `id` = '$i'");
        $obj = $db->fetch_object($s);
        $res = ($db->num_rows() >= 1) ? $obj->image : $dir.'/imgs/nopic.png';
        return($res);
    }
    function ban($id){
 global $dir;
        global $db;
        $s = $db->query("SELECT * FROM `ban` WHERE `uid` = '$id' AND `active` = '1' ORDER BY `id` DESC");
        if($db->num_rows() == 1){
            $ac = $db->fetch_object();
            if($ac->active == 1){
                return true;
            }
            else{
                return false;
            }
        }
        else{
        return false;
        }
    }
    function ipbanned($ip){
      global $db;
      $db->query("SELECT * FROM `ipban` WHERE `ip` = '$ip'");
      if($db->num_rows()>=1){
        header("Location: ipb.php");
        die("Du har blitt ip-bannet, se grunn her: <a href='ipb.php'>Grunnlag</a>");
      }
    }
    function timec($sec){
        /*Vise i minutter og sekunder*/
        $res = null;
        $min = floor($sec / 60);
        $seks= floor($sec - ($min * 60));//Resterende sekunder
        if($min >= 1){
            $res .= $min;
            if($min >= 2){
                $res .= ' minutter';
            }
            else{
                $res .= ' minutt';
            }
        }
        if($seks >= 1){
          if($min >= 1){
            $res.= " og ";
          }
            $res .= $seks;
            if($seks >= 2){
                $res .= ' sekunder';
            }
            else{
                $res .= ' sekund';
            }
        }
        return($res);
    }
    /**
     * <p>Funksjon brukes for å konvertere oppgitte brukernavn eller brukerid-er til brukernavn med status-tittel og farge tilhørende db-status.</p>
     *<p>$t = Oppgi 0 for å vurdere et brukernavn, oppgi 1 for å vurdere id.</p>
     */
    function status($s,$t=0){
 global $dir;
        //Nicket er "$s"
        global $db;
        if($t==0){
            $q = $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($s)."' LIMIT 0,1");
        }
        else if($t==1){
            $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($s)."' LIMIT 0,1");
        }
        else if($t==2){
          $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($s)."' LIMIT 0,1");
        }
        if($db->num_rows() == 1){
            $f = $db->fetch_object();
            $int = array(1,2,3,4,5,6);
            /*
            ##1:Administratorstatus
            ##2:Moderatorstatus
            ##3:Forum Moderatorstatus
            ##4:Picmaker
            ##5:Vanlig spillerstatus
            ##6:Død spillerstatus
            */
            $var = array("<span class='stat1' title='Admin'>","<span class='stat2' title='Moderator'>","<span class='stat3' title='Forum moderator'>",NULL,"<span class='stat5' title='Vanlig spiller'>","<span class='stat6' title='Død'>");
            $names=array('Admin','Moderator','Forum Moderator','Picmaker','Vanlig spiller','Død');
            $st = str_replace($int,$var,$f->status);
            if($t == 0){
                return ($st.$s.'</span>');
            }
            else if($t==1){
              if($f->status == 4){
                return '<span title="Picmaker">'.rainbow($f->user)."</span>";
              }
              else{
                return ($st.$f->user.'</span>');
              }
            }
            else if($t==2){
              $ulvl = $f->status - 1;
              echo 'rrrrrrrrrrrrrrrrrrrrr    --()()()()((Her: '.$ulvl.',status:'.$f->status.'))-- rrrrrrrrrrrrr';
              return($names[$ulvl]);
            }
        }
        else{
            return "<em title='Det finnes ingen bruker med beskrivelsen ".$s."'>Ingen</em>";
        }
    }
    /**
     * function user_exists
     * @param string $username Brukernavnet som skal sjekkes opp.
     * @param boolean $ret 1 definerer at id på brukeren skal returneres, mens 0(standard) gjør slik at det returnerer true om spilleren finnes, og false om den ikke gjør det. 2 definerer at om brukeren finnes, vil all informasjon på den brukeren bli satt som resultatet som blir sendt tilbake.
     * @return mixed $ret:0=true|false,1=uid|false,2=obj->uid|false vil returneres avhengig av resultatet og $ret.
     * 
     */
    function user_exists($username,$ret = 0){
 global $dir;
        global $db;
        $username = $db->escape($username);
        $db->query("SELECT * FROM `users` WHERE `user` = '$username'");
            if($db->num_rows() == 1){
                if($ret == 0)return true;
                if($ret == 1){
                    $f = $db->fetch_object();
                    return $f->id;
                }
                if($ret == 2){
                  return $db->fetch_object();
                }
            }
            else return false;
    }
    function firma($id){
 global $dir;
        global $db;
        $s = $db->query("SELECT * FROM `firma` WHERE `id` = '".$db->escape($id)."'");
        if($db->num_rows() == 1){
            $f = $db->fetch_object();
            return array($f->Navn,$f->Eier,$f->Type,$f->Konto,$f->By);
        }
        else{
            return false;
        }
    }
    function liv_check(){
      global $dir;
      global $obj;
      global $db;
      if($obj->health <= 0){
        return include($dir."death.php");//Henter die fil
      }
      else{
        return;
      }
    }
    function aktiv(){
 global $dir;
      global $obj;
      global $db;
      $s = $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id' AND `activated` = '0'");
      if($db->num_rows() == 1) {
        include_once($dir."inc/desp.php");/*Sjekker om spilleren er aktivert, om han ikke er blir en fil inkludert og stopper videre script som er under*/
      }
      else{
        return;
      }
    }
    function modkill_check(){
 global $dir;
        global $obj;
        global $db;
        $s = $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id' AND `moddet` = '1'");
        if($db->num_rows() >= 1) {
            return include($dir."moddet.php");//Henter die fil
        }
        else{
            return;
        }
    }
    /**
     * Sjekker om brukeren er i fengsel.
     */
    function fengsel($timereturn = NULL){
 global $dir;
        global $obj;
        global $db;
        $now = time();
        $s = $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `breaker` = '0' AND `timeleft` > '$now' ORDER BY `id` DESC LIMIT 1");
        if($timereturn == true){
            $f = $db->fetch_object();
            return ($f->timeleft - $now);
        }
        else{
            if($db->num_rows() == 0){
                return false;
            }
            else{
                return true;
            }
        }
    }
		/**
		* Sjekker om brukeren sitter i bunker
		*/
  function bunker($tr = false){
 global $dir;
  global $obj;
  global $db;
  $q = $db->query("SELECT * FROM `bunkerinv` WHERE `tid` = '".$obj->id."' AND `accepted` = '1' AND `timeleft` > ".time()." AND `used` = '1' AND `declined` = '0' AND `gone` = '0'");
  if($db->num_rows() == 1){
    if($tr){
      $g = $db->fetch_object();
      return $g->timeleft;
    }
    else return true;
  }
  else return false;
}
    /**
    * Setter inn bruker i fengselet
    * @param int $uid <p>
    * Brukerid
    * </p>
    * @param string $res <p>
    * Grunnen til at spilleren blir satt inn
    * </p>
    * @param int $timeleft <p>
    * Tiden spilleren blir innsatt.
    * </p>
    * <p>
    * @return boolean <b>settinn</b> returnerer en positiv eller negativ verdi basert pl resultatene.
    * </p>
    */
  function settinn($uid,$res = "?",$timeleft = 90){
 global $dir;
    global $db;
    $db->query("SELECT * FROM `users` WHERE `id` = '".$db->escape($uid)."'");
    $time = time() + $timeleft;
    if($db->num_rows() == 1){
      if($db->query("INSERT INTO `mafia_no_net`.`jail`(`time`,`uid`,`reason`,`timeleft`) VALUES('".time()."','".$db->escape($uid)."','".$db->escape($res)."','$time')")){
        return true;
      }
      else{
        return $db->query_error();
      }
    }
    else{
      return false;
    }
    return false;
  }
    /**
     * <p>Bbkoder for profil, nyheter og andre scripts som bruker koder.</p>
     * <p>Alle verdiene er normal satt til pl, med mindre man definerer dem med 0</p>
     * <p>Første verdien er hva som skal analyseres. De alternative mulighetene er
     * satt på til vanlig. Man kan også deaktivere dem ved å oppgi 0 i denne
     * rekkefølgen:</p>
     * <p><b>bbcodes</b>($verdi,htmlentities,full link,definert tekstlink,understreket,fet,kursiv,midtstilt,farge,bilde,strrrelse,Horisontal linjedeling,linjehopp);</p>
     */
    function bbcodes($text,$html=1,$link1=1,$link2=1,$understrek=1,$tykk=1,$kursiv=1,$midtstilt=1,$farge=1,$bilde=1,$storrelse=1,$hr=1,$linjeskift=1,$smil=1,$shadow=0,$you=0,$decode=0,$entit=1){
 global $dir;
      global $obj;
      if($html == 1){
        if($entit == 0){
        $text= htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1');
        }
        if($entit == 1){
          $text= htmlentities($text, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');
        }
      }
      if($link1 == 1){
        $text = preg_replace('#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\ text="(.+)"\]#','<a href="htt$1$2" title="$1$2">$3</a>',$text);
      }
      if($link2 == 1){
        $text = preg_replace('#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\]#i','<a href="htt$1$2">htt$1$2</a>',$text);
      }
      if($understrek == 1){
        $text = preg_replace("/\[u\](.*?)\[\/u\]/is","<span style='text-decoration:underline;'>$1</span>",$text);
      }
      if($tykk == 1){
        $text = preg_replace("/\[b\](.*?)\[\/b\]/is","<span style='font-weight:bold'>$1</span>",$text);
      }
      if($kursiv == 1){
        $text = preg_replace("/\[i\](.*?)\[\/i\]/is","<span style='font-style:italic'>$1</span>",$text);
      }
      if($midtstilt == 1){
        $text = preg_replace("/\[c\](.*?)\[\/c\]/is","<div style='text-align:center;'>$1</div>",$text);
      }
      if($farge == 1){
        $text = preg_replace("/\[f=#([0-9a-z]+)\](.*?)\[\/f\]/is","<span style=color:#$1>$2</span>",$text);
      }
      if($bilde == 1){
        $text = preg_replace("#\[img=htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)]#","<img src=\"htt$1$2\" style=\"max-width:100%;\" alt=\"\" />",$text);
      }
      if($storrelse == 1){
        $text = preg_replace('#\[size=([0-9]+)\](.*?)\[/size\]#s','<span style="font-size:$1px">$2</span>',$text);
      }
      if($hr == 1){
        $text = preg_replace("/(.*?)\[hr\](.*?)/is","$1<hr />$2",$text);
      }
      if($linjeskift == 1){
        $text = str_replace("\n","<br />",$text);
      }
      if($smil == 1){
        //$text = str_replace(array(":)",":(","[rip]","[blunk]",':O',':S',":-/",":P",":D",":rolleyes:"), array("<img src=\"chatsmile/smile.gif\" />","<img src=\"chatsmile/sad.gif\" />","<img src=\"chatsmile/rip.gif\" />","<img src=\"chatsmile/blunk.gif\" />","<img src=\"chatsmile/omg2.gif\" />","<img src=\"chatsmile/confused.gif\" />","<img src=\"chatsmile/dry.gif\" />","<img src=\"chatsmile/tongue.gif\" />","<img src=\"chatsmile/lele.gif\" />","<img src=\"smy/rolleyes.gif\" />"), $text);
        $text = str_replace(array(":)",":D",":P",":-/",";)",":(",":O","&lt;3",":S",":*"),array('<img src="smileys/Content.png" alt=":)">','<img src="smileys/Grin.png" alt=":D">','<img src="smileys/Yuck.png" alt=":P">','<img src="smileys/Slant.png" alt=":-/">','<img src="smileys/Sarcastic.png" alt=";)">','<img src="smileys/Frown.png" alt=":(">','<img src="smileys/Gasp.png" alt=":O">','<img src="smileys/Heart.png" alt="&lt;3">','<img src="smileys/Confused.png" alt=":S">','<img src="smileys/Kiss.png" alt=":*">'),$text);
      }
      if($shadow == 1){
        $text = preg_replace(array("/\[s1\](.*?)\[\/s1\]/is","/\[s2 f=\"#(.*?)\"\](.*?)\[\/s2\]/is"),array("<span style=\"text-shadow:none;text-shadow: #000000 2px 2px 2px;\">$1</span>","<span style=\"text-shadow:none;text-shadow: #$1 2px 2px 2px;\">$2</span>"),$text);
      }
      if($you){
        $text = preg_replace("/\[youtube=([a-z0-9-_]+)\?([0-1])\]/is","<iframe width=\"560\" height=\"315\" src=\"http://www.youtube.com/embed/$1?autoplay=$2\" frameborder=\"0\" allowfullscreen></iframe>",$text);
      }
      $text = preg_replace(
              array("/\<3/ix","/\[li\](.*?)\[\/li\]/is","/\[ul\](.*?)\[\/ul\]/is","/\[ol\](.*?)\[\/ol\]/is"), 
              array("&heart;","<li>$1</li>","<ul>$1</ul>","<ol>$1</ol>"), $text);
      if($decode=1){
        $text = utf8_decode($text);
      }
      $text = str_replace(array("Ã¦","Ã¸","Ã¥"), array("&aelig;","&oslash;","&aring;"), $text);
      $text = preg_replace("#\[spotify=(.+)\]#is", "<iframe src=\"https://embed.spotify.com/?uri=$1\" width=\"300\" height=\"380\" frameborder=\"0\" allowtransparency=\"true\"></iframe>", $text);
      return ($text);
    }
    function rank($xp){
    $xp2 = $xp;
    if($xp <= 50){
      $ranknr = 1;
      $rankty = "Soldat";//Ranknavn
      $maxxp = 50;
    }
    else if($xp > 50 && $xp < 100){
      $xp = $xp - 50;
      $ranknr = 2;
      $rankty = "Capo";//Ranknavn
      $maxxp = 50;
    }
    else if($xp >= 100 && $xp < 150){
      $xp = $xp - 100;
      $ranknr = 3;
      $rankty = "Underboss";//Ranknavn
      $maxxp = 50;
    }
    else if($xp >= 150 && $xp < 250){
      $xp = $xp - 150;
      $ranknr = 4;
      $rankty = "Boss";//Ranknavn
      $maxxp = 100;
    }
    else if($xp >= 250 && $xp < 350){
      $xp = $xp - 250;
      $ranknr = 5;
      $rankty = "Consigliere";//Ranknavn
      $maxxp = 350;
    }
    else if($xp >= 350 && $xp < 500){
      $xp = $xp - 350;
      $ranknr = 6;
      $rankty = "Don";//Ranknavn
      $maxxp = 500;
    }
    else if($xp >= 500 && $xp < 700){
      $xp = $xp - 500;
      $ranknr = 7;
      $rankty = "Mafioso";//Ranknavn
      $maxxp = 700;
    }
    else if($xp >= 700 && $xp < 950){
      $xp = $xp - 700;
      $ranknr = 8;
      $rankty = "Omerta";//Ranknavn
      $maxxp = 950;
    }
    else if($xp >= 950 && $xp < 1250){
      $xp = $xp - 950;
      $ranknr = 9;
      $rankty = "Vendetta";//Ranknavn
      $maxxp = 1250;
    }
    else if($xp >= 1250 && $xp < 1400){
      $xp = $xp - 1250;
      $ranknr = 10;
      $rankty = "Godfather";//Ranknavn
      $maxxp = 1400;
    }
    else if($xp >= 1400 && $xp < 3000){
      $xp = $xp - 1400;
      $ranknr = 11;
      $rankty = "Legende";//Ranknavn
      $maxxp = 2600;
    }
    else if($xp >= 3000){
      $ranknr = 12;
      $rankty = "Legendarisk Don";//Ranknavn
      $maxxp = 3000;
      if(($xp / $maxxp) > 1){
        $rankty.=" x".floor($xp / $maxxp);
      }
    }
    return array($ranknr,$rankty,$xp,$maxxp);
    }
    function mel_tit($t){
 global $dir;
      if(strlen($t) == 0)return 'Uten tittel';
      if(strlen($t) <= 7 && substr_count($t, ' ') >= 3)return 'Uten tittel';
      if(strlen($t) == substr_count($t, ' '))return 'Uten tittel';
      else return htmlentities($t,NULL,"ISO-8859-1");
    }
    function get_user($in,$ret=false){
 global $dir;
      global $db;
      $in = $db->escape($in);
      $q = $db->query("SELECT * FROM `users` WHERE `id` = '".$in."'");
      if($db->num_rows($q) == 1){
        return $db->fetch_object($q);
      }
      else return false;
    }
/**
 * (PHP 4, PHP 5)<br/>
 * Return false or true depending on user status.
 * @return boolean adminstatus
 */
    function r1(){
 global $dir;
        global $obj;
        if($obj->status == 1){
            return true;
        }
        else {return false;}
    }
/**
 * (PHP 4, PHP 5)<br/>
 * Return false or true depending on user status.
 * @return boolean moderatorstatus
 */
    function r2(){
 global $dir;
        global $obj;
        if($obj->status == 2){
            return true;
        }
        else {return false;}
    }
/**
 * (PHP 4, PHP 5)<br/>
 * Return false or true depending on user status.
 * @return boolean Forum-moderatorstatus
 */
    function r3(){
 global $dir;
        global $obj;
        if($obj->status == 3){
            return true;
        }
        else {return false;}
    }
function types($a,$b = 0){
 global $dir;
	$c=array(0,1,2,3);//Typer i tall
	$d=array("Om spillet","Om funksjoner","Feil i spillet","Klage","Forslag");//Typer definert i tekst
	if($b == 0){
		$e = str_replace($c,$d,$a);//Bytter om
	}
	else if($b == 1){
		$e = str_replace($d,$c,$a);//Bytter om
	}
	return ($e);
}
function famidtoname($id,$link = 0){
 global $dir;
	global $db;
	$db->query("SELECT * FROM `familier` WHERE `id` = '$id'")or die(mysqli_error($db->connection_id));
  if($db->num_rows() == 1){
    $navn = $db->fetch_object();
    if($link == 1){
      return '<a href="familievis.php?fam='.$navn->Navn.'">'.$navn->Navn.'</a>';
    }
    else{
      return $navn->Navn;
    }
  }
  else{
    return false;
  }
}
function sysmel($til,$melding){
 global $dir;
  global $db;
  if(is_array($til)){
    $q = "INSERT INTO `sysmail` VALUES";
    foreach ($til as $id) {
      $q.="(NULL,'$id',UNIX_TIMESTAMP(),'0','".$db->slash($melding)."'),";
    }
    $q = substr($q, -1);
    $db->query($q);
  }
  else{
    $db->query("INSERT INTO `sysmail` VALUES(NULL,'$til','".time()."','0','".$db->slash($melding)."')");
  }
}
function famlogg($spiller,$hendelse){
 global $dir;
 global $db;
 global $obj;
 $db->query("INSERT INTO `familielogg` (`familie`,`hendelse`,`time`,`spiller`) VALUES ('$obj->family','$hendelse',UNIX_TIMESTAMP(),'$spiller')");
}
/*
 * Viser feilmelding når brukeren ikke har tilgang til siden. 
 * Skal implementeres på alle admin/mod/forummod/support og
 *  picmakerpaneler der vanlige spillere normalt sett ikke har tilgang!
 */
function noaccess() {
  ?>
  	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/typed.js" type="text/javascript"></script>
    <script>
    $(function(){
        $("#typed").typed({
            // strings: ["Typed.js is a <strong>jQuery</strong> plugin.", "It <em>types</em> out sentences.", "And then deletes them.", "Try it out!"],
            stringsElement: $('#typed-strings'),
            typeSpeed: 30,
            backDelay: 500,
            loop: false,
            contentType: 'html', // or text
            // defaults to false for infinite loop
            loopCount: false,
            callback: function(){ foo(); },
            resetCallback: function() { newTyped(); }
        });
        $(".reset").click(function(){
            $("#typed").typed('reset');
        });
    });
    function newTyped(){ /* A new typed object */ }
    function foo(){ console.log("Callback"); }
    </script>
    <link href="/css/main.css" rel="stylesheet"/>
    <style>
        /* code for animated blinking cursor */
        .typed-cursor{
            opacity: 1;
            font-weight: 100;
            -webkit-animation: blink 0.7s infinite;
            -moz-animation: blink 0.7s infinite;
            -ms-animation: blink 0.7s infinite;
            -o-animation: blink 0.7s infinite;
            animation: blink 0.7s infinite;
        }
        @-keyframes blink{
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-webkit-keyframes blink{
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-moz-keyframes blink{
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-ms-keyframes blink{
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-o-keyframes blink{
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
    </style>


        <h1>Ingen tilgang!</h1>

        <div class="type-wrap">
            <div id="typed-strings">
                <font color="black"><p>Du har ikke <strong><font color="red">TILGANG</font></strong> til denne siden</p>
                <p>Dersom du mener dette er en <strong><font color="red">FEIL</font></strong> kan du kontakte support!...</p></font>
            </div>
            <span id="typed" style="white-space:pre;"></span>
        </div>

       
  <?php
}
function rainbow($text)
{
    $ret = '';
    $colors = array(
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
    $i = 0;
    $textlength = strlen($text);
    while($i<=$textlength)
    {
        foreach($colors as $value)
        {
            if ($text[$i] != "")
            {
                $ret .= '<span style="color:#'.$value.';">'.$text[$i]."</span>";
            }
        $i++;
        }
    }
    return $ret;
}
function weapons($r){
  $w = array(0=>"ingen",1=>"Colt 1911",2=>".44 Magnum",3=>"Beretta 9mm",4=>"M8A1",5=>"DSR 50",6=>"SVT-40",7=>"M4",8=>"Ak 47",9=>"M14");
  return $w[$r];
}
function weapon($r){
              $vapen = array(
              0=>array('navn' => "Ingen", 'pris' => 0, 'power' => 0),
              1=>array('navn' => "Colt", 'pris' => 84200, 'power' => 1),
              2=>array('navn' => "Glock 64", 'pris' => 147400, 'power' => 2),
              3=>array('navn' => "Dual Berettas", 'pris' => 294800, 'power' => 3),
              4=>array('navn' => "Desert Eagle", 'pris' => 874200, 'power' => 4),
              5=>array('navn' => "MP5", 'pris' => 1623000, 'power' => 5),
              6=>array('navn' => "PP Bizon", 'pris' => 4125000, 'power' => 6),
              7=>array('navn' => "P90", 'pris' => 8250000, 'power' => 7),
              8=>array('navn' => "AK-47", 'pris' => 16500000, 'power' => 8),
              9=>array('navn' => "M4A4", 'pris' => 33000000, 'power' => 9),
              10=>array('navn' => "Magnum Sniper Rifle", 'pris' => 66000000, 'power' => 10)
          );
return $vapen[$r]['navn'];
}
/**
 * 
 * @param type $t Resultat feilet, setter feilet paragraf-class rundt tekst &lt;p class="feil"&gt;$text&lt;/p&gt;
 */
function feil($t){
  echo'<p class="feil">'.$t.'</p>';
}
/**
 * 
 * @param type $t Resultat lykket, setter lykket paragraf-class rundt tekst &lt;p class="lykket"&gt;$text&lt;/p&gt;
 */
function lykket($t){
  echo '<p class="lykket">'.$t.'</p>';
}