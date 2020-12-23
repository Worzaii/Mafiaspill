<?php
include 'core.php';
$res=null;
if (isset($_POST['submit'])) {
    $on = $db->escape($_POST['online']);
    $on = (is_numeric($on) || empty($on)) ? (($on == 1) ? 1 : 0) : false;
    if ($on == 1) {
        $db->query("UPDATE `users` SET `settings` = '" . serialize(array('o' => $on)) . "' WHERE `id` = '{$obj->id}' LIMIT 1");
        if ($db->affected_rows() == 1) {
            $res = "<p>Du har endret pålogget til den nye versjonen!</p>";
            $obj->settings = serialize(array("o" => 1));
        }
    } else if ($on == 0) {
        $db->query("UPDATE `users` SET `settings` = '" . serialize(array('o' => $on)) . "' WHERE `id` = '{$obj->id}' LIMIT 1");
        if ($db->affected_rows() == 1) {
            $res = "<p>Du har endret pålogget til den gamle versjonen!</p>";
            $obj->settings = serialize(array("o" => 0));
        }
    }
}
$check = unserialize($obj->settings);
if ($check["o"] == 1) {
    $onl = " checked";
}
startpage("Endre innstillinger");
echo '<h1>Endre innstillinger for din bruker</h1>
  '.$res.'
    <form method="post" action="">
    <input type="checkbox"'.$onl.' name="online" value="1">&nbsp;&nbsp;Vis den nye "Spillere pålogget" ved trykk på link i venstremeny.<br>
    <input type="submit" value="Oppdater" name="submit">
    </form>';
endpage();
