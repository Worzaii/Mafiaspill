<?php

define("LVL", true);
include "../core.php";
header('Content-type: application/json');
$str = ['string' => feil('Error: Action not set!'), 'state' => 0];
if (isset($_POST["action"]) && isset($_POST["id"])) {
    $ac = $_POST['action'];
    $id = $_POST['id'];
    $pre = $db->prepare("select count(*) as numrows from mafia.news where id = ? and userlevel >= ?");
    $pre->execute([$id, $obj->status]);
    if ($pre->fetchColumn() == 1) {
        if ($ac == 1) {
            $str['string'] = "Endring ikke implementert";
        } elseif ($ac == 2) {
            $pre = $db->prepare("delete from news where id = ? and userlevel >= ? limit 1");
            $pre->execute([$id, $obj->status]);
            if ($pre->rowCount() == 1) {
                $str = ['string' => "Nyheten har blitt slettet.", 'state' => 1];
            } else {
                $str['string'] = "Kunne ikke slette nyheten...";
            }
        } elseif ($ac == 3) {
            $get = $db->prepare("SELECT showing from news where id = :id and userlevel >= :ul");
            $get->bindParam(":id", $id, PDO::PARAM_INT);
            $get->bindParam(":ul", $obj->status, PDO::PARAM_INT);
            $get->execute();
            $showing = $get->fetchColumn();
            $change = ($showing == 1) ? 0 : 1;
            $switch = $db->prepare(
                "UPDATE news SET showing = ? WHERE id = ? and showing = ? and userlevel >= ? LIMIT 1"
            );
            $switch->execute([$change, $id, $showing, $obj->status]);
            if ($switch->rowCount() == 1) {
                $str['string'] = "Nyheten med ID $id har blitt satt som " . (($change == 1) ? "synlig" : "skjult") . "!";
                $str['state'] = 1;
                $str['newstatus'] = $change;
            } else {
                $str['string'] = "Kunne ikke endre synlighet på nyheten!";
            }
        }
    } else {
        $str["string"] = "Kunne ikke finne nyheten...";
    }
    //$str["string"] = "I changed this... You send ID: " . $_POST['id'] . " with the action: " . (($_POST['action'] == 1) ? "Change" : (($_POST['action'] == 2) ? "Delete" : "VisibilityToggle"));
}
echo json_encode($str);
