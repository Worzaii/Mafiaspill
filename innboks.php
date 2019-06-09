<?php
include("core.php");
startpage("Innboks");
?>
    <h1>Innboks</h1>
    <p class="info">Her har du mulighet til å komme i privat kontakt med andre spillere, hvis de ikke har blokkert deg.
        Du har også muligheten til å blokkere spillere som er til plage eller ellers her. Om du har spørsmål, gjerne ta
        kontakt med support så hjelper vi så godt vi kan.</p>
    <ul class="nav">
        <li><a href="./innboks.php">Innboks</a></li>
        <li><a href="./innboks.php?page=utboks">Utboks</a></li>
        <li><a href="./innboks.php?page=sperringer">Sperringer</a></li>
        <li><a href="./innboks.php?page=sperringer">Support</a></li>
        <li><a href="./innboks.php?page=ny">Ny melding</a></li>
    </ul>
<?php
if (!isset($_GET['page'])) {
    /* Showing inbox as a natural first page*/
    $q = $db->query("select * from mafia.mails where uid = '{$obj->id}' and deleted = '0' order by id desc ");
    if ($q->num_rows == 0) {
        echo info("Du har ingen meldinger.");
    } else {
        echo '<table class="table"><tr><th>Tema</th><th>Avsender</th><th>Dato</th></tr>';
        while ($r = mysqli_fetch_object($q)) {
            echo "<tr>
<td onclick='goto(" . $r->id . ")'>$r->title</td>
<td><a href='profil.php?id=" . $r->uid . "' data-id='" . $r->uid . "' data-user='' class='user_menu'>" . status($r->uid) . "</a></td>
<td>" . date("d.m.y H:i:s", $r->timestamp) . "</td></tr>";
        }
        echo '</table>';
    }
} else {
    $page = (string) $_GET['page'];
    if (in_array($page, ['utboks', 'sperringer', 'support', 'les', 'ny'])) {
        if ($page === 'les') {
            $id = (int) $_GET['id'];
            $q = $db->query("select * from mafia.mails where tid = '" . $obj->id . "' and id = '$id'");
            if ($q->num_rows == 1) {
                $res = mysqli_fetch_object($q);
                $user = user($res->uid, 1)->user;
                $title = $res->title;
                $message = $res->message;
                $date = date("H:i:s d.m.Y", $res->timestamp);
                echo <<<END
<table class="table" style="margin-top: 15px;">
    <thead>
        <tr>
            <th colspan="3" style="text-align: center">$title</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Avsender: $user</td><td>Mottatt: $date</td><td>Handling kommer...</td>
        </tr>
        <tr><td colspan="3" style="text-align: left">$message</td></tr>
    </tbody>
</table>

END;
            } else {
                echo feil('Det finnes ingen melding til deg med den IDen. 
                Er du sikker på at den ikke er slettet?');
            }
        } elseif ($page === 'ny') {
            if (isset($_GET['user'])) {
                $user = $_GET['user'];
                if (is_string($user) || is_int($user)) {
                    $to = user_exists($user, 2)->user;
                } else {
                    echo warning("Feil bruker, finnes ikke... Trykket feil?");
                }
            } else {
                $to = null;
            }
            echo <<<END
<form method="post"
      action="">
    <table class="table"
           style="width: 400px;margin-top: 15px;">
        <tr>
            <th colspan="2">Skriv en melding til en bruker</th>
        </tr>
        <tr>
            <th>Til:</th>
            <td><input type="text"
                       name="user"
                       value="$to"
                       placeholder="Brukernavn"></td>
        </tr>
        <tr>
            <th>Tema:</th>
            <td><input type="text"
                       name="title"
                       placeholder="Tema"></td>
        </tr>
        <tr>
            <th colspan="2"
                style="text-align: center">Melding:
            </th>
        </tr>
        <tr>
            <td colspan="2"><textarea name="message"
                                      placeholder="Skriv en melding her."></textarea></td>
        </tr>
        <tr>
            <th colspan="2"><input type="submit"
                                   value="Send melding!"
                                   name="submit"></th>
        </tr>
    </table>
</form>
END;
        }
        /* Add more handlers for the page here... */
    } else {
        echo warning('Innboks inneholder ikke en slik side, prøv igjen.');
    }
}
?>
    <script>
        function goto(id) {
            window.location.href = "innboks.php?page=les&id=" + id;
        }
    </script>
<?php
endpage();
