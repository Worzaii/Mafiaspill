<?php
define("BASEPATH", TRUE);
include './system/config.php';
include("./classes/class.php");
$db  = new database;
$db->configure();
$db->connect();
$ipq = $db->query("SELECT * FROM `ipban` WHERE `ip`='".$_SERVER['REMOTE_ADDR']."'");
if ($db->num_rows() == 1) {
    $ip = $db->fetch_object();
    ?>
    <link rel="stylesheet" href="style.css" type="text/css">
    <table width="25%" class="C2">
        <tr>
            <th colspan="1" align="center">Du har blitt IP-Bannet!</th>
        </tr>

        <?php
        echo "
<tr>
<td>Ip-adressen: <u>".$ip->ip."</u> er blitt utestengt!<br><br>
Grunn: ".$ip->grunn."<br>
Bannet dato: ".date("H:i:s d.m.Y", $ip->dato)."
</tr>";

        echo '
</table>
<p>Om du mener at denne IP-bannen er gjort p&aring; gale vilk&aring;r/grunnlag, send en mail til denne mailadressen: <a href="mailto:'.HENVEND_MAIL.'?subject=Ip-ban%20p&aring%20'.$_SERVER['REMOTE_ADDR'].'">'.HENVEND_MAIL.'</a></p><br><br><a href="loggut.php">Logg ut!</a>';
    } else {
        header("Location: /nyheter.php");
    }

