<?php
include 'core.php';
if (r1() || r2()) {
    startpage("$obj->user");
    ?>
    <h1><?= $obj->user ?></h1>
    <?php
    if (isset($_POST['peong'])) {
        $db->query("UPDATE `users` SET `points` = '" . intval($_POST['peong']) . "',`exp` = '" . $db->escape($_POST['epx']) . "',`hand`='" . intval($_POST['dnah']) . "',`bank`='" . intval($_POST['kanb']) . "',`bullets`='" . $db->escape($_POST['kuls']) . "' WHERE `id` = '$obj->id' LIMIT 1");
        if ($db->affected_rows() == 1) {
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
                <td>Poeng: <input style="float:right;" name="peong" type="number" value="<?= $obj->points ?>"></td>
            </tr>
            <tr>
                <td>Exp: <input style="float:right;" name="epx" type="text" value="<?= $obj->exp ?>"><br></td>
            </tr>
            <tr>
                <td>Penger p&aring; handa: <input style="float:right;" name="dnah" type="number"
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
    endpage();
} else {
    startpage("Ingen tilgang");
    noaccess();
    endpage();
}
?>