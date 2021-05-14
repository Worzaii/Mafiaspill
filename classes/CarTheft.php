<?php

namespace UserObject;

use PDO;

class CarTheft
{
public User $user;
public object $database;
public string $out = "";

/**
 * CarTheft constructor
 */
public function __construct(User $user, PDO $database)
{
    $this->user = $user;
    $this->database = $database;
    $this->execute();
}

private function execute()
{
    if ($write = canUseFunction(1, 1)) {
        $this->out .= $write;
    } else {
        $this->readyTheft();
    }
    $this->loadPage();
}

public function loadPage()
{
    startpage("Biltyveri");
    echo '<h1>Biltyveri</h1><img src="images/headers/biltyveri.png"><p>Når du først starter med biltyveri, så vil du kun ha et valg. Ettersom du kommer opp i rank, så vil nye valg låses opp.</p>';
    echo $this->out;
    endpage();
}

public function waitText($time)
{
    return '
        <p class="warning">Du må vente <span id="biltyveri">' . ($time - time()) . '</span> før neste krim.</p>
        <script>
        teller(' . ($time - time()) . ', "biltyveri", false, "ned");
        </script>
        ';
}

private function listCarChoices()
{
$this->out .= <<<END
<div id="biltyveri">
<form method="post" id="bil" action="">
<table class="table" style="width:590px;">
<tr>
<th colspan="3">Biltyveri</th>
</tr>
<tr class="c_3">
<td>Oppgave</td>
<td>Sjanse</td>
<td>Ventetid</td>
</tr>
END;
$s = $this->database->prepare(
    "SELECT count(*) FROM `cars` WHERE `levelmin` <= ?
                        ORDER BY `levelmin` DESC,`id` DESC"
);
$s->execute([$this->user->exp->getRank()]);
if ($s->fetchColumn() >= 1) {
    while ($r = mysqli_fetch_object($s)) {
        $sql2 = $this->database->prepare(
            "SELECT count(*) FROM `chance` WHERE `uid` = ? AND `type` = '2' AND `option` = ?"
        );
        $sql2->execute([$this->user->getId(), $r->id]);
        if ($sql2->fetchColumn() >= 1) {
            $sql2 = $this->database->prepare(
                "SELECT * FROM `chance`WHERE `uid` = ? AND `type` = '2' AND `option` = ?"
            );
            $get2 = mysqli_fetch_object($sql2);
        } else {
            $chan = $this->database->prepare(
                "INSERT INTO `chance`(`uid`,`type`,`option`)
VALUES(?,'2',?)"
            );
            $chan->execute([$this->user->getId(), $r->id]);
        }
        echo '
            <tr class="valg" onclick="sendpost(' . $r->id . ')">
            <td>' . htmlentities(
                $r->choice,
                ENT_NOQUOTES | ENT_HTML401,
                "UTF-8"
            ) . '</td><td>' . ((!is_numeric($get2->chance))
                ? 0 : $get2->chance) . '%</td><td>' . timec($r->timewait) . '</td>
            </tr>
            ';
    }
} else {
    echo '<tr><td colspan="3"><em>Ingen biltyverier kan bli tatt akkurat nå!</em></td></tr>';
}

?>
</table>
<input type="hidden"
       value=""
       name="valget"
       id="valget">
</form>
<script language="javascript">
    function sendpost(valg) {
        $('#valget').val(valg);
        $('#bil').submit();
    }

    $(document).ready(function () {
        $('.valg').hover(function () {
            $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
        }, function () {
            $(this).removeClass().css('cursor', 'pointer');
        });
    });
</script>
</div>
END;
}

private function doTheft($choice)
{

}

private function tryTheft($choice)
{

}

private function readyTheft()
{
$r1 = $this->database->prepare("SELECT count(*) FROM `carslog` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 0,1");
$r1->execute([$this->user->getId()]);
if ($r1->fetchColumn() == 0) {
/* Can do theft */
$this->listCarChoices();
} else {
$r2 = $this->database->prepare("SELECT (timewait - unix_timestamp()) as timeleft from carslog where uid = ? and timewait > unix_timestamp() order by id desc limit 0,1");
$r2->execute([]);
$this->out .= $this->waitText($r2->fetchColumn());
}
}
}
