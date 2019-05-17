<?php
include("core.php");
startpage("Chat");
?>
    <h1>Chat - Snakk med andre spillere</h1>
<?php
if ($obj->status == 1 || $obj->status == 2 || $obj->status == 3) {
    echo <<<END
    <p class="feil"><a href="tomprat.php" onclick="return confirm('Sikker p&aring; at du vil t&oslash;mme prat?')">T&oslash;m prat - Burde ikke t&oslash;mmes f&oslash;r det er minst 3 dager gammelt, eller over 150 beskjeder i praten!</a></p>
    <p class="feil"><a href="visprat.php">Vis hele prat databasen</a></p>
END;
}
?>
<?php
$s = $db->query("SELECT * FROM `forumban` WHERE `uid` = '{$obj->id}' AND `bantime` > UNIX_TIMESTAMP() AND `active` = '1' ORDER BY `bantime` DESC LIMIT 1");
if ($db->num_rows() == 1) {
    $f = $db->fetch_object($s);
    $timeleft = $f->bantime - time();
    echo '
	<div style="border-width: 2px; border-style: dotted; border-color: red; "><p class="feil"> Du er utestengt fra forumet!</p>
	</br><b>Du har blitt utestengt av forumet av ' . user($f->banner) . ' og derfor kan du se chatten men uten tilgang til &aring; skrive.</br>
    Du har </h3><span id="user' . $f->uid . '"></span><script>teller(' . $timeleft . ',"user' . $f->uid . '","true","ned";)</script> igjen av straffen din. Hvis du mener du har f&aring;tt den ved en feiltagelse, kontakt Support!</br>
	</br>
	Grunnen for at du ble utestengt er: ' . $f->res . '</b></div></br>
	  
	';
} else {
    echo <<<HTML
    </br>
<input style="font-size: 11px;border: 1px solid #aaa;-webkit-border-radius: 4px;height: 17px; width: 568px;margin-left: 9px;margin-top: -6px;margin-bottom: 12px;background: #FFFFFF;padding: 5px;" name="write" type="text" id="write" placeholder="Enter for &aring; skrive">
    </br>
HTML;
}
?>
    <div id="praten"></div>
    <script src="js/nyprat.js" type="text/javascript"></script>
<?php
endpage();
