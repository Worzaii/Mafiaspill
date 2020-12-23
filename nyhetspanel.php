<?php
/*
 * TODO: Consider adding colors to the different sections to make it easier to see for visibility and status.
 *
 * TODO: Here's a test query to update all existing news with random accepted values:
 * update news set userlevel = FLOOR(RAND()*(5-1+1))+1;
 */
include "core.php";
if (r1() || r2()) {
    startpage("Administrer nyheter");
    echo "<h1>Nyhetsadministrering</h1>" .
        "<p class=\"button2\"><a href=\"publiser.php\">Skriv en ny nyhet!</a></p>";
    $news = $db->prepare("select * from news where userlevel >= ? order by id desc");
    $news->execute([$obj->status]);
    $rows = "";
    while ($new = $news->fetchObject()) {
        $showing = ($new->showing === 1) ? "Ja" : "Nei";
        switch ($new->userlevel) {
            case 1:
                $level = "Admin";
                break;
            case 2:
                $level = "Mod";
                break;
            case 3:
                $level = "Forum-mod";
                break;
            case 4:
                $level = "Picmaker";
                break;
            case 5:
                $level = "Alle";
                break;
            default:
                $level = "FEIL";
                break;
        }
        $rows .= <<<HTML
<tr id='tr$new->id'>
    <td>$new->id</td>
    <td>$new->title</td>
    <td>$level</td>
    <td>$showing</td>
    <td><button onclick='return choice($new->id,1)'>Endre</button><button onclick='return deletenews($new->id,2)'>Slett</button><button onclick='return choice($new->id,3)'>Vis/skjul</button></td>
</tr>
HTML;
    }
    //$rows .= "<tr><td colspan='5'><input type='submit' value='Utfør nå!'></td></tr>";
    echo <<<HTML
<script type="text/javascript" src="js/newshandler.js"></script>
<div id="newsresult"></div>
<table class="table newshandler">
    <thead>
        <tr>
            <th colspan="5">Registrerte nyheter i databasen</th>
        </tr>
        <tr>
            <th>Id</th><th>Tittel</th><th>Tilgang</th><th>Vises</th><th>Behandle</th>
        </tr>
    </thead>
    <tbody>
    $rows
    </tbody>
</table>
HTML;

} else {
    noaccess();
}
endpage();