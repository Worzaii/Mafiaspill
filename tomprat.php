<?php
    include("core.php");
    if($obj->status == 1 || $obj->status == 2 || $obj->status == 3){
    startpage("T&oslash;mmer prat");
    $dato2 = date("H.i.s_d.m.Y");
    $fp = fopen('chatlog/chat_'.$dato2.'.txt', 'a');
    $g = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC");
   $d = date("H:i:s d.m.y");
    $string = utf8_encode("T&oslash;mming av prat klokken {$dato2} av {$obj->user}!");
    while($r = mysqli_fetch_object($g)){
        $idser = $r->uid;
        $i = $r->mld;
        $i = str_replace("&oslash;","&oslash;",$i);
        $i = str_replace("&aring;","&aring;",$i);
        $i = str_replace("&aelig;","&aelig;",$i);
        $i = str_replace("Æ","&AElig;",$i);
        $i = str_replace("Ø","&Oslash;",$i);
        $i = str_replace("&aring;","&Aring;",$i);
        $user = $db->query("SELECT * FROM `users` WHERE `id` = '$idser'");
        $ubj = $db->fetch_object();
$string .= "
[".date('H:i:s d.m.y',$r->time)."] {$ubj->user}: {$i}";
    }
fwrite($fp, $string);
fclose($fp);
if(!$db->query("TRUNCATE TABLE `chat`")){echo '<p>Det var et problem ved t&oslash;mming av praten!</p>';}else{
$db->query("INSERT INTO `chat`(`id`,`uid`,`mld`,`time`) VALUES (NULL,'0','".utf8_encode("Praten ble t&oslash;mt av ".$obj->user."!")."','".time("H.i.s d-m-Y")."')");
echo'
<h1>Chatten har blitt t&oslash;mt!</h1>
<p>Du har n&aring; renset chatten, og en melding ble skrevet i prat for deg.</br>';
}
}
else{startpage("Ingen tilgang!");
echo '<h1>Ingen tilgang!</h1><p>Denne siden er kun for Forum-moderatorer og h&oslash;yere.</p>';
}
endpage();
?>