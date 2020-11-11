<?php
include("core.php");
startpage("Spillstatistikk");
?>
    <h1>Statistikk</h1>
<?php
$select = $db->query("SELECT * FROM `users` ORDER BY `id` DESC LIMIT 0,10");
$select2 = $db->query("SELECT * FROM `users` ORDER BY `id` DESC");
$select3 = $db->query("SELECT * FROM `users` WHERE `status` <> '1' AND `status` <> '2' AND `health` > '0' ORDER BY `exp` DESC LIMIT 10");
$select4 = $db->query("SELECT * FROM `users` WHERE `status` <> '1' AND `status` <> '2' AND `health` = '0'");
echo '<table class="tablemain">
<tr>
    <td colspan="2">
        <table class="table">
            <thead>
                <th colspan="2">Spill-statistikk</th>
            </thead>
            <tbody>
                <tr><td>Antall spillere registrert:</td><td>' . $select2->num_rows . '</td></tr>
                <!--<tr><td>Antall aktiverte spillere:</td><td></td></tr>-->
                <tr><td>Antall døde spillere:</td><td>' . $select4->num_rows . '</td></tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Siste 10 registrerte:</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>ID:</td>
                <td>Bruker:</td>
            </tr>
        ';
while ($r = mysqli_fetch_object($select)) {
    echo '
                <tr>
                    <td>#' . $r->id . '</td>
                    <td><strong><a href="profil.php?id=' . $r->id . '">' . status($r->user) . '</a></strong></td>
                </tr>
        ';
}
echo '
            </tbody>
        </table>
    </td>
    <td>';
if ($obj->status == 1 || $obj->status == 2) {
    $exp = '<th>Exp:</th>';
    $col = ' colspan="2"';
} else {
    $exp = null;
    $col = null;
}
echo '
  <table id="rank" class="table">
  <tr>
  <th>Topp 10 rank</th>' . $exp . '</tr>';
while ($r = mysqli_fetch_object($select3)) {
    if ($obj->status == 1 || $obj->status == 2) {
        $kr = '<td>' . $r->exp . '</td>';
    } else {
        $kr = null;
    }
    echo '<tr><td><a href="profil.php?id=' . $r->id . '">' . status($r->user) . '</a></td>' . $kr . '</tr>';
}
    echo '</table></td>
</tr>
<tr>
';

echo '<td>';
if ($obj->status == 1 || $obj->status == 2) {
    $ex = '<th>Banksum:</th>';
    $col = ' colspan="2"';
} else {
    $ex = null;
    $col = null;
}
echo '<table id="bank" class="table">
<tr>
<th>Topp 10 bank</th>' . $ex . '</tr>';

while ($r = mysqli_fetch_object($select4)) {
    if ($obj->status == 1 || $obj->status == 2) {
        $kr = '<td>' . number_format($r->bank) . ' kr</td>';
    } else {
        $kr = null;
    }
    echo '<tr><td><a href="profil.php?id=' . $r->id . '">' . status($r->user) . '</a></td>' . $kr . '</tr>';
}
echo '</table>';

?>
    </td></tr></table>
<?php
/*$srr = $db->query("SELECT * FROM `familier` WHERE `lagtned` = '0' ORDER BY `TimeMade` ASC") or die("Feil: " . mysqli_error($db->con));
echo '
 <div class="familie w500">
 <h1 class="big">Familieoversikt</h1>
 <table style="width:auto;" class="table center">
 ';
if ($db->num_rows() == 0) {
    echo '<tr><td>Ingen familier eksisterer enda.</td></tr>';
} else {
    while ($r = mysqli_fetch_object($srr)) {
        if ($stopwhile == 1) {
        } else {
            $stopwhile = 1;
            echo '
 <thead>
   <tr class="c_1">
       <td colspan="2">Familie</td>
       <td>Boss</td>
	   <td>Underboss</td>
 </tr>
 </thead>
 <tbody>
 ';
        }

        echo '

         <tr class="c_2">
          <td><a href="familievis.php?fam=' . $r->Navn . '"><img src="' . $r->img . '" alt="" height="35" width="35"></a></td>
          <td><a href="familievis.php?fam=' . $r->Navn . '">' . $r->Navn . '</a></td>
          <td><a href="profil.php?id=' . $r->id . '">' . user($r->Leder) . '</a></td>
          <td><a href="profil.php?id=' . $r->id . '">' . user($r->Ub) . '</a></td>
         </tr>
';
        echo '</tbody>';
    }
}

echo '</table>';
echo '</div>';*/
?>
<style>
    table.tablemain>td, table.tablemain>th{
        vertical-align:top;
        margin: 0 auto;
        padding-bottom: 15px;
    }
    table.tablemain{
        margin: 0 auto;
        width: 90%;
    }
</style>
<?php
endpage();
