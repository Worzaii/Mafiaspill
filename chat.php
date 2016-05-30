<?php
include("core.php");
$style='<style type="text/css" media="screen">

a.prat {
 display: inline-block;
 margin-top: 10px;
 padding: 10px 25px;
 background: #006BC2;
 border-radius: 10px;
 font-size: 23px;
 color: #FFFFFF;
 border-top: 1px solid #EEEEEE;
 text-decoration: underline;
        font-family: "Sessions";
    border: 1px solid #FFFFFF;
}
input[type="submit"].prat {
    background-color: #323232;
    border: 2px solid #DEDEDE;
    color: #DEDEDE;
}


</style>';
startpage("Chat",$style);
?>
<h1>Chat - Snakk med andre spillere</h1>
<?php
if($obj->status == 1 || $obj->status == 2 || $obj->status == 3){
    echo <<<END
    <p class="feil"><a href="tomprat.php" onclick="return confirm('Sikker på at du vil tømme prat?')">Tøm prat - Burde ikke tømmes før det er minst 3 dager gammelt, eller over 150 beskjeder i praten!</a></p>
    <p class="feil"><a href="visprat.php">Vis hele prat databasen</a></p>
END;
}
?>
<?php
$time = time();
$s = $db->query("SELECT * FROM `forumban` WHERE `uid` = '$obj->id' AND `timeleft` > '$time' AND `act` = '1' ORDER BY `timeleft` DESC LIMIT 1");
if($db->num_rows() == 1){
    $f = $db->fetch_object($s);
    $timeleft = $f->timeleft - time();
	echo '
	<div style="border-width: 2px; border-style: dotted; border-color: red; "><p class="feil"> Du er utestengt fra forumet!</p>
	</br><b>Du har blitt utestengt av forumet av '.user($f->banid).' og derfor kan du se chatten men uten tilgang til å skrive.</br>
    Du har </h3><span id="user'.$f->uid.'"></span><script type="text/javascript">teller('.$timeleft.',"user'.$f->uid.'","true","ned");</script> igjen av straffen din.</br>
	</br>
	Grunnen for at du ble utestengt er: '.$f->res.'</b></div></br>
	  
	';
}
else{
echo <<<HTML
    </br>
<input style="font-size: 11px;border: 1px solid #aaa;-webkit-border-radius: 4px;height: 17px; width: 568px;margin-left: 9px;margin-top: -6px;margin-bottom: 12px;background: #FFFFFF;padding: 5px;" name="write" type="text" id="write" placeholder="Enter for å skrive">
    </br>
HTML;
}
?>
<div id="praten"></div>
<script src="js/nyprat.js" type="text/javascript"></script>
<?php
endpage();
?>