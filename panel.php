<?php
include("core.php");
startpage("Paneler");
if (r1() || r2() || r3() || support()) {
    if (r1()) {
        ?>
        <h1 style="text-align: center">Administrasjon</h1>
        <h3 style="text-align:center"> Test Funksjoner</h3>
        <ul class="adminpanel">
            <li><a href="#">Ingen for &oslash;yeblikket.</a></li>
        </ul>
        <p class="center"> Admin funksjoner</p>
        <ul class="adminpanel">
            <li><a href="ipban.php">Internet Protocol Adresse blokkering :D</a></li>
            <li><a href="bankalle.php">Siste bankoverf&oslash;ringer(100 siste)</a></li>
            <li><a href="endre_spiller.php">Sjekk opp spiller(Ikke klar, arbeides med)</a></li>
            <li><a href="player_logoff.php">Logg ut en spiller!</a></li>
            <li><a href="antinlog.php">Se antall innlogginger!</a></li>
            <li><a href="publiser.php">Legg ut ny nyhet!</a></li>
            <li><a href="modkill2.php"><em>Modkill spiller</em></a></li>
            <li><a href="/Multizone">Multi-muligheter</a></li>
            <li><a href="stilling.php">Sett stilling til en spiller!</a></li>
            <li><a href="endrespiller.php">Endre spillers verdier!</a></li>
            <li><a href="modkilletvis.php">Se alle som er modkillet</a></li>
            <li><a href="poenglogg.php">Poenglogg</a></li>
            <li><a href="auksjonlogg.php">Auksjonslogg</a></li>
            <li><a href="stillinglogg.php">Stillingslogg</a></li>
        </ul>
        <?php
    }
    if (r1() || r2()) { ?>
        <p class="center">Moderatorpanel</p>
        <ul class="adminpanel">
            <li><a href="bankalle.php">Bankoverf&oslash;ringer</a></li>
            <li><a href="publiser.php">Legge til en nyhet</a></li>
            <li><a href="ipsjekk.php">Sjekk spillere som kan bruke multi</a></li>
            <li><a href="modkill2.php">Modkill spiller</a></li>
            <li><a href="poenglogg.php">Poeng-logg</a></li>
            <li><a href="modkilletvis.php">Se alle som er modkillet</a></li>
            <li><a href="edityourself.php">Endre egne verdier</a></li>
            <li><a href="faq_panel.php">FAQ Panel</a></li>
        </ul>
        <?php
    }
    if (r1() || r2() || r3()) {
        ?>
        <p class="center"> Forummod funksjoner</p>
        <ul class="adminpanel">
            <li><a href="visprat.php">Vis hele prat databasen!</a></li>
            <li><a href="forumban.php">Forumban spiller</a></li>
            <li><a href="tomprat.php">Loggf&oslash;r og t&oslash;m praten!</a></li>
            <li><a href="chatlog.php">Vis loggf&oslash;rte chat logger</a></li>
        </ul>
        <?php
    }
    if (r1() || r2() || support()) {
        /* Soon implementing the support panel feature here. */
    }
    echo '<br>'; /* Creating some spacing under the last table, whichever shows for the user */
} else {
    noaccess();
}
endpage();
