<?php
include "core.php";
if (r1() || r2()) {
    startpage("Administrer nyheter");
    echo "<h1>Nyhetsadministrering</h1>" .
        "<p class=\"button2\"><a href=\"publiser.php\">Skriv en ny nyhet!</a></p>";
    $news = $db->prepare("select * from news order by id desc");
    $news->execute();
    $rows = "";
    while ($new = $news->fetchObject()) {
        $rows .= <<<HTML
<tr id='tr$new->id'>
    <td>$new->id</td>
    <td>$new->title</td>
    <td>$new->userlevel</td>
    <td>$new->showing</td>
    <td><button onclick='return choice($new->id,1)'>Endre</button><button onclick='return deletenews($new->id,2)'>Slett</button><button onclick='return choice($new->id,3)'>Vis/skjul</button></td>
</tr>
HTML;
    }
    //$rows .= "<tr><td colspan='5'><input type='submit' value='Utf&oslash;r n&aring;!'></td></tr>";
    echo <<<HTML
<script type="text/javascript" src="js/newshandler.js"></script>
<div id="newsresult"></div>
<table class="table">
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