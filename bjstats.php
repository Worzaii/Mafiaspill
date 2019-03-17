<?php
    include("core.php");
    startpage("BlackJack Statistikk");
    if(isset($_GET['seal'])){
?>
<h1>BlackJack statistikk, summert vinn</h1>
<p><a href="bjstats.php">&lArr;Tilbake til taprunder</a></p>
<table class="table" style="width:300px;">
    <thead>
        <tr>
            <th colspan="2">Viser summert</th>
        </tr>
        <tr>
            <th>Bruker</th><th>Sum</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $s = $db->query("SELECT `uid`,SUM(`result`) AS `summ` FROM `bjtables` GROUP BY `uid` ORDER BY `summ` DESC")or die(mysqli_error());
        while ($r = mysqli_fetch_object($s)) {
            $q = $db->query("SELECT sum(`result`) AS `res` FROM `bjtables` WHERE `uid` = '$r->uid'");
            $re = mysqli_fetch_object($q);
            echo '
            <tr>
                <td>'.user($r->uid,0).'</td><td>'.number_format($re->res).'</td>
            </tr>';
        }
        ?>
    </tbody>
</table>
<?php
    }
    else{
    $s = $db->query("SELECT `uid`,COUNT(`uid`) AS `loss` FROM `bjtables` WHERE `result` < '0' GROUP BY `uid` ORDER BY `loss` ASC")or die(mysqli_error());
?>
<h1>BlackJack statistikk</h1>
<?
if(r1() || r2()){
    echo '<p><a href="bjstats.php?seal">Vis sum av alle spill.&rArr;</a></p>';
}
?>
<table class="table" style="width:300px;">
    <thead>
        <tr>
            <th colspan="3">Mest tapte runder:</th>
        </tr>
        <tr>
            <th>Bruker</th><th>Runder</th><th>Tap:</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($r = mysqli_fetch_object($s)) {
            $q = $db->query("SELECT sum(`result`) AS `res` FROM `bjtables` WHERE `uid` = '$r->uid' AND `result` < 0");
            $re = mysqli_fetch_object($q);
            echo '
            <tr>
                <td>'.user($r->uid,0).'</td><td>'.$r->loss.'</td><td>'.number_format($re->res).'</td>
            </tr>';
        }
        ?>
    </tbody>
</table>
<?php
}
    endpage();
?>