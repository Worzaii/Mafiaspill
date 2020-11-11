<?php
include("core.php");
startpage("Verving");
?>
<h1 style="background:#0052A5">Verving</h1>
<table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
    <tr>
        <th>Brukernavn</th><th>Gyldig/Ikke gyldig</th>
    </tr>
    <?php
    $sql = $db->query("SELECT * FROM `users` WHERE `vervetav` = '$obj->id';");
    while ($r   = mysqli_fetch_object($sql)) {
        if ($r->exp > 99.999) { //gyldig hvist brukeren er rank 3 eller høyere
            $st = "<span style=\"color:green;font-weight:bold;\">Gyldig</span>";
        } else if ($r->exp <> 99.998) {//Ikke gyldig hvist brukeren er under rank 3
            $st = "<span style=\"color:red;font-weight:bold;\">Ikke gyldig</span>";
        }
        echo '
    <tr>
        <td>'.user($r->id).'  </td>
            <td><span id="luser'.$r->id.'"></span><b>'.$st.'</b></td>
    </tr>
    ';
    }
    ?>
</table>
</br>
<center><p>Hei <b><?= $obj->user; ?></b>, nå er det mulig og verve spillere for noen fine premier :)</br>
        For å verve noen må du sende de weblink til spillet. Deretter må vedkommende oppgi din verve kode i "vervet av" feltet.<br>
        Din verve kode: <b><font color="red"><?= $obj->id; ?></font></b><br>
        <b><font color="red">NB!:</font> Spillere på samme ip regnes ikke som gyldig!</b></p><br><br>
    <table class="table" style="margin-top: 1px; text-align: center; width: 540px;"> Ekstra premier frem til 3. desember :)
        <th>Premie</th><th>Vervede</th>
        <tr><td>25 poeng + 5 poeng</td><td>1 Stk</td></tr>
        <tr><td>60 poeng + 10 poeng</td><td>3 Stk</td></tr>
        <tr><td>1 mnd spotify premium + 10 poeng</td><td>10 Stk</td></tr>
        <tr><td>125 poeng + 20 poeng</td><td>15 Stk</td></tr>
    </table>

    <?php
    endpage();
    ?>
