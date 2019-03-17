<?php
define('BASEPATH', true);
require_once('system/config.php');
?>
<html>
    <body>
        <table border="1" style="border-collapse: collapse">
            <thead>
                <tr><th>Type</th><th>Verdi</th></tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SERVER as $type => $verdi) {
                    echo '<tr><td>'.$type.'</td><td>'.$verdi.'</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <table border="1" style="border-collapse: collapse">
            <thead>
                <tr><th>Type</th><th>Verdi</th></tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION as $type => $verdi) {
                    echo '<tr><td>'.$type.'</td><td>'.$verdi.'</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
