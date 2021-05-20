<?php

include("core.php");
$news = new News(user:$obj, database: $db, title: "Publiser nyhet!");
die();
if (r1() || r2()) {
    startpage("Publiser nyheter");
    ?>
    <h1>Publiser nyheter</h1>
    <div style="margin:0 auto;text-align:center;width:90%;">
        <?php
        if (isset($_POST['melding'])) {
            $tema = $_POST['tema'];
            $mel = $_POST['melding'];
            $lvl = $_POST['rangerank'];
            if (strlen($mel) <= 2) {
                echo '<p>Meldingen er for kort!</p>';
            } else {
                $new = $db->prepare(
                    "INSERT INTO `news`(`title`,`text`,`author`,`timestamp`,`showing`,`userlevel`) VALUES(?,?,?,UNIX_TIMESTAMP(),1,?)"
                );
                try {
                    if ($new->execute([$tema, $mel, $obj->id, $lvl])) {
                        echo lykket("Du har publisert en nyhet!");
                        if ($lvl == 5) {
                            /* Only announce news if it's for everyone */
                            $chatnews = $db->prepare(
                                "insert into chat values(null,(select id from users where user='System' limit 1),concat('En nyhet med tema ', ?, ' har blitt postet av ',?), unix_timestamp())"
                            );
                            try {
                                $chatnews->execute([$tema, $obj->user]);
                                echo lykket("En melding har blitt lagt i chat om at nyheten har blitt publisert!");
                            } catch (PDOException $e) {
                                echo feil($e->getMessage());
                            }
                        }
                    } else {
                        error_log(
                            "Couldn't execute this query: " . $new->queryString . ". Got this error: " . $new->errorInfo(
                            )[2]
                        );
                        echo feil('Kunne ikke publisere nyheten, se loggen for feilmeldinger.');
                    }
                } catch (PDOException $exception) {
                    echo feil("Kunne ikke utføre på grunn av følgende feil: " . $exception->getMessage());
                }
            }
        }
        ?>
        <form method="post"
              action="">
            <p>Tittel:<input type="text"
                             name="tema"
                             value=""></p>
            <p class="info">Dra baren for å justere hvem som kan se nyheten.</p>
            <input id="rang"
                   type="range"
                   name="rangerank"
                   min="1"
                   max="5"
                   value="5"><br><span
                id="rankrange">Alle</span>
            <p>Melding:</p>
            <textarea name="melding"
                      style="width:100%;height:250px;"></textarea> <input type="submit"
                                                                          value="Publiser nyheten!">
        </form>
    </div>
    <script type="text/javascript">
        $('#rang').change(function () {
            let $sp = $('#rankrange');

            let vis = [
                '<span style=\'color:#f00\'>Administrator</span>',
                '<span style=\'color:#f00\'>Administrator</span> & <span style=\'color:#5151ff\'>Moderator</span>',
                '<span style=\'color:#f00\'>Administrator</span> & <span style=\'color:#5151ff\'>Moderator</span> & <span style=\'color:#0f0\'>Forum-moderator</span>',
                '<span style=\'color:#f00\'>Administrator</span> & <span style=\'color:#5151ff\'>Moderator</span> & <span style=\'color:#0f0\'>Forum-moderator</span> & Picmaker',
                'Alle'];
            let val = $('#rang').val();
            $sp.html(vis[val - 1]);
        });
    </script>
    <?php
} else {
    startpage("Ingen tilgang!");
    echo '<h1>Ingen tilgang!</h1>';
}
endpage();

