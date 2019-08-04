<?php
include("core.php");
if (r1()) {
    startpage("Stillinger");
    ?>
    <h1>Endre spillerstatus</h1>
    <?php
    $stilling = [
        1 => "Administrator",
        2 => "Moderator",
        3 => "Forum Moderator",
        4 => "Picmaker",
        5 => "Vanlig spiller"
    ];
    if (isset($_POST['user']) && isset($_POST['status']) && isset($_POST['sub'])) {
        $user = $db->escape($_POST['user']);
        $status = $db->escape($_POST['status']);
        $support = $db->escape($_POST['support']);
        if (!is_numeric($status) || $status <= 0 || $status >= 6) {
            feil("Status er ikke et nummer, er for h&oslash;yt eller for lavt, du valgte nr. : " . $status . "!");
        } else {
            $query = $db->query("SELECT * FROM `users` WHERE `user` = '$user'");
            $num = $db->num_rows();
            if ($num == 1) {
                $uid2 = $db->fetch_object($query);
                $db->query("INSERT INTO `stillingslogg`(`uid`,`pid`,`old_status`,`new_status`,`timestamp`) VALUES('$obj->id','$uid2->id','$uid2->status','$status',UNIX_TIMESTAMP())");
                if (!$db->query("UPDATE `users` SET `status` = '$status',`support` = '" . $support . "' WHERE `user` = '$user'")) {
                    echo feil('Spilleren fikk ikke stillingen! ' . mysqli_error($db->con) . '');
                } else {
                    echo info('Spilleren har n&aring; blitt oppdatert!');
                    if ($status != 5) {
                        sysmel(
                            $uid2->id,
                            '--<b>Ledelsen</b></br>Du har n&aring; blitt satt opp som ' . $stilling[$status] . '.<br>
Vi &oslash;nsker deg lykke til!'
                        );
                    }
                }
            }
        }
    }
    if (isset($_GET['nick'])) {
        $kill = $db->escape($_GET['nick']);
        $sporring = $db->query("SELECT * FROM `users` WHERE `user` = '$kill'");
        if ($db->num_rows($sporring) == 0) {
            //Bruker ikke funnet i db
            $asd = null;
        } else {
            $asc = $db->fetch_object($sporring);
            $asd = $asc->user;
        }
    }
    ?>
    <form method="post">
        <table class="table">
            <thead>
            <tr>
                <th colspan="2">Skriv inn spillernavn og velg type</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Brukernavn:</td>
                <td><input placeholder="Brukernavnet" value="<?= $asd ?>" name="user" type="text" autofocus=""/></td>
            </tr>
            <tr>
                <td>Velg status:</td>
                <td>
                    <select name="status" size="6" style="color:#000">
                        <option value="1">Administrator</option>
                        <option value="2">Moderator</option>
                        <option value="3">Forum-Moderator</option>
                        <option value="4">Picmaker</option>
                        <option selected value="5">Vanlig spiller</option>
                    </select>
                    <br>
                    <p>Kan brukeren svare p&aring; support?</p>
                    <input type="radio" name="support" value="1">Ja
                    <input type="radio" name="support" value="0" checked="">Nei
                </td>
            </tr>
            <tr>
                <th colspan="2"><input type="submit" name="sub" value="Endre statusen"></th>
            </tr>
            </tbody>
        </table>
    </form>
    <?php
} else {
    startpage("Ingen tilgang!");
    noaccess();
}
endpage();
