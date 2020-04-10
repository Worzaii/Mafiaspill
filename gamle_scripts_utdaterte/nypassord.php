<?php
include("core.php");
if (isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['newpass2']) && isset($_POST['submit'])) {
    $old = $_POST['oldpass'];
    $new = $_POST['newpass'];
    $ne2 = $_POST['newpass2'];
    $res = null;
    if (strlen($_POST['oldpass']) <= 2 || strlen($_POST['newpass']) <= 2 || strlen($_POST['newpass']) <= 2 || $_POST['newpass'] != $_POST['newpass2'] || !password_verify($old, $obj->pass)) {
        $res .= feil('Det har oppst&aring;tt en eller flere feil:');
        if (strlen($_POST['oldpass']) <= 2) {
            $res .= feil('Det gamle passordet var for kort eller ikke oppgitt!');
        }
        if (strlen($_POST['newpass']) <= 2) {
            $res .= feil('Det nye passordet var for kort eller ikke oppgitt!');
        }
        if (strlen($_POST['newpass2']) <= 2) {
            $res .= feil('Det gjentatte passordet var for kort eller ikke oppgitt!');
        }
        if ($_POST['newpass'] != $_POST['newpass2']) {
            $res .= feil('De nye passordene var ikke like, pr&oslash;v igjen!');
        }
        if (!password_verify($old, $obj->pass)) {
            $res .= feil('<u>Det gamle passordet du oppgav var ikke riktig!</u>');
        }
    } else {/* Ingen feil, pr&oslash;ver &aring; endre passordet*/
        $newhash = password_hash($new, PASSWORD_DEFAULT);
        if ($db->query("UPDATE `users` SET `pass` = '".$newhash."' WHERE `id` = '{$obj->id}' AND `pass` = '{$obj->pass}' LIMIT 1")) {
            $_SESSION['sessionzar'] = [$obj->user, $newhash];
            $res .= lykket('Passordet er oppdatert!');
        } else {
            if ($obj->status == 1) {
                $res .= feil('Feil i oppdatering: ' . mysqli_error());
            } else {
                $res .= feil('Kunne ikke oppdatere passordet! Kontakt ledelsen for hjelp, da det kan inneholde feil i scriptet!');
            }
        }
    }
}
startpage("Endre passord");
?>
    <h1>Endre passord</h1>
<?php
if (isset($res)) {
    echo $res;
} ?>
    <form method="post"
          action="">
        <table class="table"
               style="width: 240px;">
            <tr>
                <th colspan="2">Endre passord</th>
            </tr>
            <tr>
                <td>Gammelt passord:</td>
                <td><input type="password"
                           name="oldpass"></td>
            </tr>
            <tr>
                <td>Nytt passord:</td>
                <td><input type="password"
                           name="newpass"></td>
            </tr>
            <tr>
                <td>Gjenta passord:</td>
                <td><input type="password"
                           name="newpass2"></td>
            </tr>
            <tr>
                <th colspan="2"><input type="submit"
                                       name="submit"
                                       value="Endre passordet!"></th>
            </tr>
        </table>
    </form>
<?php
endpage();
?>