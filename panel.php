<?php
include("core.php");
startpage("Paneler");
if (r1() || r2() || r3() || support()) {
    if (r1()) {
        ?>
        <h1 class="center">Administrasjon</h1>
        <h3 class="center" style="clear: both"> Test Funksjoner</h3>
        <ul class="adminpanel">
            <li><a href="#">Ingen for &oslash;yeblikket.</a></li>
        </ul>
        <h3 class="center" style="clear: both"> Admin funksjoner</h3>
        <ul class="adminpanel">
            <li><a href="ipban.php">Adresseblokkering</a></li>
            <li><a href="#">Spill-logger <span style="color: #ff0000;">(Ikke klar)</span></a></li>
            <li><a href="#">Spiller-informasjon</a></li>
            <li><a href="player_logoff.php">Logg ut en spiller!</a></li>
            <li><a href="publiser.php">Nyhetsadministrasjon</a></li>
            <li style="display: inline;float: left;width:50%;border-right: 1px solid #000"><a href="ban_user.php">Ban en
                    spiller</a></li>
            <li style="display: inline;float: left;width:50%;"><a href="ban_list.php">Ban-liste</a></li>
            <li><a href="/Multizone">Multi-muligheter</a></li>
            <li><a href="stilling.php">Sett stilling til en spiller!</a></li>
            <li><a href="endrespiller.php">Endre spillers verdier!</a></li>
            <li><a href="ban_list.php">Se alle som er modkillet</a></li>
            <li><a href="poenglogg.php">Poenglogg</a></li>
            <li><a href="auksjonlogg.php">Auksjonslogg</a></li>
            <li><a href="stillinglogg.php">Stillingslogg</a></li>
        </ul>
        <?php
    } elseif (r1() || r2()) { ?>
        <h3 class="center" style="clear: both">Moderering</h3>
        <ul class="adminpanel">
            <li><a href="bankalle.php">Bankoverf&oslash;ringer</a></li>
            <li><a href="publiser.php">Legge til en nyhet</a></li>
            <li><a href="ipsjekk.php">Sjekk spillere som kan bruke multi</a></li>
            <li><a href="ban_user.php">Modkill spiller</a></li>
            <li><a href="ban_list.php">Ban-liste</a></li>
            <li><a href="poenglogg.php">Poeng-logg</a></li>
            <li><a href="ban_list.php">Se alle som er modkillet</a></li>
            <li><a href="edityourself.php">Endre egne verdier</a></li>
            <li><a href="faq_panel.php">FAQ Panel</a></li>
        </ul>
        <?php
    } elseif (r1() || r2() || r3()) {
        ?>
        <h1>Forum moderering</h1>
        <h3 class="center" style="clear: both"> Forummod funksjoner</h3>
        <ul class="adminpanel">
            <li><a href="visprat.php">Vis hele prat databasen!</a></li>
            <li><a href="forumban.php">Forumban spiller</a></li>
            <li><a href="tomprat.php">Loggf&oslash;r og t&oslash;m praten!</a></li>
            <li><a href="chatlog.php">Vis loggf&oslash;rte chat logger</a></li>
        </ul>
        <?php
    } elseif (r1() || r2() || support()) {
        /* Soon implementing the support panel feature here. */
        ?>
        <h1>Supportpage to be</h1>
        <?php
    }
    echo '<br>'; /* Creating some spacing under the last table, whichever shows for the user */
} else {
    noaccess();
}
endpage();
