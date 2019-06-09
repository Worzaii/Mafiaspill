<?php
include("core.php");
if (r1() || r2()) {
    startpage("Publiser nyheter");
    ?>
    <h1>Legg inn ny nyhet</h1>
    <div style="margin:0 auto;text-align:center;width:90%;">
        <?php
        if (isset($_POST['melding'])) {
            $tema = $db->escape($_POST['tema']);
            $mel = $db->escape($_POST['melding']);
            $lvl = $db->escape($_POST['rangerank']);
            if (strlen($mel) <= 2) {
                echo '<p>Meldingen er for kort!</p>';
            } else {
                if (!$db->query("INSERT INTO `news`(`title`,`text`,`author`,`timestamp`,`showing`,`userlevel`) VALUES('$tema','$mel','$obj->user',UNIX_TIMESTAMP(),'1','$lvl')")) {
                    if ($obj->status == 1) {
                        echo feil('Kunne ikke publisere nyheten, se loggen for feilmeldinger.');
                    }
                } else {
                    echo lykket("Du har publisert en nyhet!");
                    if ($lvl == 5) {
                        /* Only announce news if it's for everyone */
                        $db->query("INSERT INTO `chat` (`id`, `uid`, `message`, `timestamp`) 
VALUES (NULL, '0', '{$obj->user} skrev akkurat en nyhet med tittelen ".$tema."', UNIX_TIMESTAMP());");
                    }
                }
            }
        }
        ?>
        <form method="post" action="">
            <p>Tittel:<input type="text" name="tema" value=""></p>
            <p class="info">Dra baren for &aring; justere hvem som kan se nyheten.</p>
            <input id="rang" type="range" name="rangerank" min="1" max="5" value="5"><br><span
                    id="rankrange">Alle</span>
            <p>Melding:</p>
            <textarea name="melding" style="width:100%;height:250px;"></textarea>
            <input type="submit" value="Publiser nyheten!">
        </form>
    </div>
    <script>
        $("#rang").change(function () {
            let $sp = $("#rankrange");

            let vis = ["<span style='color:#f00'>Administrator</span>", "<span style='color:#f00'>Administrator</span> & <span style='color:#5151ff'>Moderator</span>", "<span style='color:#f00'>Administrator</span> & <span style='color:#5151ff'>Moderator</span> & <span style='color:#0f0'>Forum-moderator</span>", "<span style='color:#f00'>Administrator</span> & <span style='color:#5151ff'>Moderator</span> & <span style='color:#0f0'>Forum-moderator</span> & Picmaker", "Alle"];
            let val = $("#rang").val();
            $sp.html(vis[val - 1]);
        });
    </script>
    <?php
} else {
    startpage("Ingen tilgang!");
    echo '<h1>Ingen tilgang!</h1>';
}
endpage();

