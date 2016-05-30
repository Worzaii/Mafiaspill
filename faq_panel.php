<?php
include("core.php");
startpage("FAQ Panel");
if(r1() || r2()){
    $query = $db->query("SELECT * FROM `faq`");
    ?>
<form action="" method="POST">
    <table style="width: 380px;" class="table">
        <tr><td>Tittel</td><td><a href="faq_panel.php?ny">Ny!</a></td></tr>
        <?php
        while($r = mysqli_fetch_object($query)){
        echo '<tr><td>'.$r->title.'</td><td><a href="faq_panel.php?endre='.$r->id.'">Endre || </a><a href="faq_panel.php?slett='.$r->id.'">Slett</a></td></tr>';
    }
    ?>
    </table>
</form>
<?php
if(isset($_GET['ny'])){
if(isset($_POST['faq_ny'])){
    //Dobbelsjekk at admin/mod
        $title = $db->escape($_POST['title']);
        $innhold = $db->escape($_POST['faq_innhold']);
        $db->query("INSERT INTO `faq` (`title`,`innhold`,`who`,`time`) VALUES ('$title','$innhold','$obj->id',UNIX_TIMESTAMP())");
        echo '<p class="lykket">Du la ut nytt innlegg i FAQ.</p>';
}
    ?>
<form action="" method="post">
    <table class="table">
        <tr>
            <th>Nytt innlegg</th>
        </tr>
        <tr>
            <td>
                Tittel: <input type="text" name="title"/>
                <textarea style="margin: 2px;min-height: 155px;min-width: 570px;" name="faq_innhold"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="faq_ny" value="Legg ut"/>
            </td>
        </tr>
    </table>
</form>
<?php
}
elseif(isset($_GET['endre'])){
    $id = $db->escape($_GET['endre']);
    $sporring = $db->query("SELECT * FROM `faq` WHERE `id` = '$id'");
    $hent = $db->fetch_object($sporring);
    if(isset($_POST['faq_ny'])){
        $title = $db->escape($_POST['title']);
        $innhold = $db->escape($_POST['faq_innhold']);
        $sporring = $db->query("UPDATE `faq` SET `title` = '$title', `innhold` = '$innhold' WHERE `id` = '$id' LIMIT 1");
        echo '<p class="lykket">Du endret ett innlegg i FAQ.</p>';
}
    
 ?>
<form action="" method="post">
    <table class="table">
        <tr>
            <th>Nytt innlegg</th>
        </tr>
        <tr>
            <td>
                Tittel: <input type="text" value="<?=$hent->title?>"name="title"/>
                <textarea style="margin: 2px;min-height: 155px;min-width: 570px;" name="faq_innhold"><?=$hent->innhold?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="faq_ny" value="Legg ut"/>
            </td>
        </tr>
    </table>
</form>
<?php
}
}else{noaccess();}
endpage();
?>