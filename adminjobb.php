<?php
include("core.php");
include("pagination.php");
startpage("Hva jobber du med?");
// Bare admins kan være på denne siden og gjøre endringer.
if($obj->status == 1){
// Scriptet
?>
<form action="" method="POST">
<input type="text" name="arbeid" placeholder="Arbeidsbeskrivelse"/>
<input type="text" name="script" placeholder="Script"/>
<input type="submit" name="sender" value="Send!"/>
</form>
    <?php
$query = $db->query("SELECT * FROM `adminjobb`");
if($db->num_rows($query) == 0){
    echo '<p class="feil">Ingenting ble funnet. Burde kanskje starte med noe her?</p>';
}else{
    if(isset($_POST['sender'])){
        $arbeid = $db->escape($_POST['arbeid']);
        $script = $db->escape($_POST['script']);
        $db->query("INSERT INTO `adminjobb` (`script`,`arbeid`,`fremgang`,`uid`) VALUES ('$script','$arbeid','1','$obj->id')");        
    }
    if(isset($_POST['endre_status'])){
        $id = $db->escape($_POST['playerId']);
        $fremgang = $db->escape($_POST['statusen']);
        $db->query("UPDATE `adminjobb` SET `fremgang` = '$fremgang' WHERE `id` = '$id'");
    }
    $sql = "SELECT * FROM `adminjobb` ORDER BY `id` DESC";
    $pagination = new Pagination($db,$sql, 10,'p');
    $pagination_links = $pagination->GetPageLinks();
    $adminjobb = $pagination->GetSQLRows();
    $fremgang = array(
        0=>array('id' => 0, 'navn' => "Ikke påbegynt"),
        1=>array('id' => 1, 'navn' => "Påbegynt"),
        2=>array('id' => 2, 'navn' => "Feil her / Får ikke til"),
        3=>array('id' => 3, 'navn' => "Ferdig")
    );
?>
<table class="table">
    <th colspan="6">Oppgaver</th>
    <tr><td>Arbeid</td><td>Script</td><td>Fremgang</td><td>Hvem jobber med det?</td><td>Endre Status</td></tr>
    <?php
    foreach($adminjobb as $adminjobber){
    echo '<tr><td>'.bbcodes($adminjobber['arbeid'],1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0).'</td><td>'.$adminjobber['script'].'</td><td>'.$fremgang[$adminjobber['fremgang']]['navn'].'</td><td>'.user($adminjobber['uid']).'</td><td>
<form action="" method="post">        
<select name="statusen">
            <option value="0">Ikke påbegynt</option>
            <option value="1">Påbegynt</option>
            <option value="2">Feil her / Får ikke til</option>
            <option value="3">Ferdig</option>
            </select><input type="submit" name="endre_status" value="Endre status!" /> 
            <input type="hidden" name="playerId" value="'.$adminjobber['id'].'" />
</form> </td></tr>';
    }
    echo '<tr><td colspan="6">'.$pagination_links.'</td></tr>';
    ?>
</table>
<?php
}
endpage();
}else{noaccess();}
?>