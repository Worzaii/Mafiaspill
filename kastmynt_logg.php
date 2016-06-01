<?php
include("core.php");
include("pagination.php");
startpage("Kast Mynt Logg");
$query = $db->query("SELECT * FROM `kastmynt_logg`");
if($db->num_rows($query) == 0){
    echo '<p class="feil">Ingen tabeller ble funnet.</p>';
}else{
    $fetch = $db->fetch_object($query);
    if(isset($_POST['search_post'])){
        $search = $db->escape($_POST['search']);
        $query = $db->query("SELECT * FROM `kastmynt_logg` WHERE `name` = '$search'");
        if($db->num_rows($query) == 0){
            echo '<p class="feil">Ingen tabeller ble funnet!</p>';
        }else{
            $fetch = $db->fetch_object($query);
              $sql = "SELECT * FROM `kastmynt_logg` WHERE `name` = '$search' ORDER BY `id` DESC";
              $pagination = new Pagination($db,$sql, 20,'p');
              $pagination_links = $pagination->GetPageLinks();
              $kastmynt_search = $pagination->GetSQLRows();
            ?>
<table class="table">
    <th colspan="5">Resultat</th>
    <tr><td>Spiller</td><td>Innsats</td><td>Gevinst</td><td>Totalt vinn/tap</td><td>Dato</td></tr>
    <?php
    foreach($kastmynt_search as $kastmynt){
        echo '<tr><td>'.$kastmynt['name'].'</td><td>'.$kastmynt['innsats'].'</td><td>'.$kastmynt['gevinst'].'</td><td>'.$kastmynt['total_win_loss'].'</td><td>'.date("H:i:s d-m-Y",$kastmynt['time']).'</td></tr>';
    }
    echo '<tr><td colspan="5">'.$pagination_links.'</td></tr>';
    ?>
</table>
<?php
        }
    }
              $sql = "SELECT * FROM `kastmynt_logg` ORDER BY `id` DESC";
              $pagination = new Pagination($db,$sql, 20,'p');
              $pagination_links = $pagination->GetPageLinks();
              $kastmynt_logg = $pagination->GetSQLRows();
    ?>
<form action="" method="post">
    <table class="table">
        <th colspan="2">Søk etter spiller</th>
        <tr><td><input type="text" name="search"/></td><td><input type="submit" name="search_post"></td></tr>
    </table>
</form>
<form action="" method="post">
    <table class="table">
        <th colspan="6">Kast mynt logg</th>
        <tr><td>Spiller</td><td>Satset</td><td>Gevinst</td><td>Totalt vinn/tap</td><td>Dato</td></tr>
        <?php
        foreach($kastmynt_logg as $klogg){
            echo '<tr><td>'.user($klogg['uid']).'</td><td>'.$klogg['innsats'].'</td><td>'.$klogg['gevinst'].'</td><td>'.$klogg['total_win_loss'].'</td><td>'.date("H:i:s d-m-Y",$klogg['time']).'</td></tr>';
        }
        echo '<tr><td colspan="6">'.$pagination_links.'</td></tr>';
        ?>
    </table>
</form>
<?php
}
endpage();
?>