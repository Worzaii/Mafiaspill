<?php
include 'core.php';
if (r1() || r2()) {
    startpage("$obj->user");
    ?>
    <h1><?= $obj->user ?></h1>
    <?php
    if (isset($_POST['poeng'])) {
        $points = intval($_POST['poeng']);
        $exp = $db->escape($_POST['epx']);
        $hand = intval($_POST['dnah']);
        $bank = intval($_POST['kanb']);
        $bullets = $db->escape($_POST['kuls']);
        $db->query("UPDATE `users` SET `points` = '$points',`exp` = '$exp',`hand`='$hand',`bank`='$bank',
`bullets`='$bullets' WHERE `id` = '$obj->id' LIMIT 1");
        if ($db->affected_rows() == 1) {
            $db->query("INSERT INTO `selfedit`(id, uid, bank_old, bank_new, exp_old, exp_new, hand_old, hand_new, 
                       bullets_old, bullets_new,points_old,points_new,timestamp) 
                       VALUES (NULL,'$obj->id','$obj->bank','$bank','$obj->exp','$exp','$obj->hand','$hand',
                               '$obj->bullets','$bullets','$obj->points','$points',UNIX_TIMESTAMP())");
            echo '<p class="lykket">Oppdatert!';
        } else {
            echo feil('Kunne ikke oppdatere!<br>' . $db->get_last_error() . '');
        }
    }
    ?>
    <form method="post" action="">
        <table style="width:50%;" class="table">
            <tr>
                <th>Endre egne verdier</th>
            </tr>
            <tr>
                <td>Poeng: <input style="float:right;" name="poeng" type="number" value="<?= $obj->points ?>"></td>
            </tr>
            <tr>
                <td>Exp: <input style="float:right;" name="epx" type="text" value="<?= $obj->exp ?>"><br></td>
            </tr>
            <tr>
                <td>Penger på handa: <input style="float:right;" name="dnah" type="number"
                                                  value="<?= $obj->hand ?>"></td>
            </tr>
            <tr>
                <td>Penger i banken: <input style="float:right;" name="kanb" type="number" value="<?= $obj->bank ?>">
                </td>
            </tr>
            <tr>
                <td>Kuler: <input style="float:right;" name="kuls" type="number" value="<?= $obj->bullets ?>"></td>
            </tr>
        </table>
        <input type="submit" value="Lagre">
    </form>
    <?php
} else {
    startpage("Ingen tilgang");
    noaccess();
}
endpage();
