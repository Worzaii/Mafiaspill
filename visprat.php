<?php
include("core.php");
startpage("Viser hele prat databasen!");
if($obj->status >=4){
echo '<h1>Ingen tilgang!</h1>';
echo '<p class="feil">Du har ikke tilgang hit!</p>';
die();
endpage();
}
echo '<h1>Viser hele prat!</h1>';
    $chat = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC");
    if(isset($_SESSION['chatwarning'])){
        if(($_SESSION['chatwarning']['time'] - time()) >= 0){
            echo $_SESSION['chatwarning']['string'];
        }
        else{
            unset($_SESSION['chatwarning']);
        }
    }
    while($r = mysqli_fetch_object($chat)){
        $teksten = $r->mld;
        $teksten = htmlentities($teksten, ENT_NOQUOTES, 'UTF-8');
        $uob = user($r->uid,1);
        if($r->uid == 0){
          $uob = "Systemet";
        }
        else{
          $uob = '<a href="profil.php?id='.$uob->id.'">'.$uob->user.'</a>';
        }
        if($uob->status == 1){
            $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
        }
        else if($uob->status == 2 || $uob->status == 6){
            $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
        }
        else{$teksten = bbcodes($teksten, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0);}
        //echo '<p>uob: '.$uob->status.'</p>';
        if($r->id % 2){
            echo
            '<div class="chat ct1"><b>['.date("H:i:s d.m.y",$r->time).']</b> &lt;'.$uob.'&gt;: <br /><span class="chattext">'.$teksten.'</span></div>';
        }
        else{
            echo
            '<div class="chat ct2"><b>['.date("H:i:s d.m.y",$r->time).']</b> &lt;'.$uob.'&gt;: <br /><span class="chattext">'.$teksten.'</span></div>';
        }
    }
endpage();
?>