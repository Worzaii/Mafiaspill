<?php
    include("core.php");
    if($obj->status <= 3){
    $style=NULL;
    startpage("Supportpanel",$style);
?>
<h1>Supportpanelet</h1>
<?php
    function check($txt){
    if(strlen($txt) == 0){
    $txt = 'Uten tittel';
    }
    return($txt);
    }
    echo <<<ENDHTML
    <table class="table" style="width:600px;margin:0px auto;">
    <tr>
    <th colspan="4" style="text-align:center;">Meldinger:</th>
    </tr>
    <tr>
    <td>Tittel</td><td>Sendt fra</td><td>Sendt til</td><td>Dato</td><td>Slett</td>
    </tr>
ENDHTML;
    if($obj->status == 3)
    $getmes = $db->query("SELECT * FROM `support` WHERE `til_hvem` = '$obj->id' AND `treated` = '0' ORDER BY `id` DESC LIMIT 10")or die(mysqli_error());
    while($r = mysqli_fetch_object($getmes)){
        echo '<tr><td>'.check($r->theme).'</td><td>'.  user($r->usid, 0).'</td><td>'.towho($r->til_hvem).'</td><td>'.date("H:i:s d.m.y",$r->time).'</td><td>?</td></tr>';
    }
    echo '</table>';
    }
    else{
        startpage("Ingen tilgang");
        echo '<h1>Ingen tilgang</h1><p>Du er ikke en forum-moderator med supportrettigheter, derfor kan du ikke vise denne siden!</p>';
    }
    endpage();
?>