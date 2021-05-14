<?php

define('BASEPATH', true);
require_once('system/config.php');
if (isset($_SESSION['sessionzar'])) {
    header("Location: /nyheter.php");
} elseif (isset($_SESSION['grunn'])) {
    $grunn = $_SESSION['grunn'];
    unset($_SESSION['grunn']);
} else {
    $grunn = null;
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <title><?= NAVN_DOMENE; ?></title>
    <link rel="stylesheet"
          href="css/login.css">
    <meta http-equiv="content-type"
          content="text/html;charset=UTF-8">
    <meta name="description"
          content="<?= DESC; ?>">
    <meta name="keywords"
          content="<?= KEYWORDS; ?>">
    <meta name="author"
          content="<?= UTVIKLER; ?>">
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/handler.js"></script>
    <script src="js/tabs.js"></script>
</head>
<body>
<header>
    <div id="header"></div>
</header>
<section>
    <div class="wrapper">
        <div id="shadow"></div>
        <div id="information">
            <p>Dette er en melding for å vise varsler i forhold til spillet.<br> Det vil inkluderes en funksjon i
                spillet for å oppdatere denne i adminpanelet på et tidspunkt.</p>
        </div>
        <div id="content">
            <ul>
                <li><a href="#login">Innlogging</a></li>
                <li><a href="#register">Registrering</a></li>
                <li><a href="#forgotpassword">Glemt passord</a></li>
                <li><a href="#rules">Regler</a></li>
                <li><a href="#info">Informasjon</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
            </ul>
            <div id="login">
                <h2>Innlogging</h2>
                <?= (isset($grunn) ? $grunn : "") ?>
                <div id="loginresult"></div>
                <noscript><p>Du må ha javascript aktivert for å spille!</p></noscript>
                <form class="loginform"
                      name="logininfo"
                      id="loginform"
                      action="handlers/handler.php?login">
                    <fieldset id="loginfield"
                              style="border: 0;">

                        Brukernavn: <input autocomplete="username"
                                           class="text"
                                           disabled
                                           name="username"
                                           placeholder="Brukernavn"
                                           value="<?= (isset($_SERVER['DEV']) && $_SERVER['DEV'] =
                                                   '1') ? "user" : null; ?>"
                                           required
                                           type="text"><br> Passord:<input autocomplete="password"
                                                                           class="text"
                                                                           disabled
                                                                           name="password"
                                                                           placeholder="Passord"
                                                                           value="<?= (isset($_SERVER['DEV']) && $_SERVER['DEV'] =
                                                                                   '1') ? "user" : null; ?>"
                                                                           required
                                                                           type="password"><br><br>
                        <input class="button"
                               disabled
                               type="Submit"
                               value="Logg inn">
                    </fieldset>
                </form>
                <div class="cleanify"></div>
            </div>
            <div id="register">
                <h2>Motta Invitasjon</h2>
                <div id="registerresult"></div>
                <p>Du kan kun sende &eacute;n mail hver halvtime, så om du ikke klarer det første gangen må du vente en
                    stund, så pass på at du skriver riktig e-postadresse når du skal motta registreringslink.
                    Alternativt, ta kontakt med ledelsen på <?= HENVEND_MAIL_SAFE ?></p>
                <form class="loginform"
                      name="reginfo"
                      id="getaccessform"
                      action="handlers/handler.php?getaccess">
                    E-post:<input type="email"
                                  autocomplete="username"
                                  class="text"
                                  name="email"
                                  required
                                  placeholder="E-post"><br> <input type="Submit"
                                                                   value="Registrer"
                                                                   class="button">
                </form>
                <div class="cleanify"></div>
            </div>
            <div id="forgotpassword">
                <h2>Nytt passord</h2>
                <div id="forgotpasswordresult"></div>
                <form class="loginform"
                      name="passinfo"
                      id="forgotpasswordform"
                      action="handlers/handler.php?forgotpassword">
                    <input type="text"
                           class="text"
                           name="user"
                           placeholder="Brukernavn"><br> <input type="text"
                                                                class="text"
                                                                name="mail"
                                                                placeholder="E-post"><br> <input type="Submit"
                                                                                                 value="Tilbakestill passord"
                                                                                                 class="button">
                </form>
                <div class="cleanify"></div>

            </div>
            <div id="rules">
                <h2>Reglement</h2>
                <div class="regler">
                    <div class="wrap">
                        <h3>1. Brukerkonto</h3>
                        <ol>
                            <li>Du har ikke lov å bruke flere brukerkontoer samtidig. Men om en person spiller sammen
                                med deg på samme nett, så er du pålagt å si ifra til Ledelsen direkte via Melding. Er
                                ingen i ledelsen aktive, så får du vente med å si det til senere. Evt. send det inn på
                                Support slik at vi kan lese det senere.
                            </li>
                            <li>Du er den eneste som har ansvaret for brukeren, det vil si at du ikke har lov til å la
                                andre bruke din bruker til å få erfaring for deg.
                            </li>
                            <li>Du har ikke på noen måte lov å bruke et program/javascript eller lignende til å få
                                brukeren din til å ranke opp imens du ikke er tilstede. Det skal alltid være du som
                                ranker spilleren, ingenting annet.
                            </li>
                        </ol>
                        <h3>2. <em title="Chat/Forum/Meldinger/Support">Kommunikasjon</em></h3>
                        <ol>
                            <li>Der du kan dele og skrive med andre vil det være restriksjoner til hva du kan skrive. Se
                                under:
                                <ol>
                                    <li>Du har ikke lov å reklamere for noen nettsider. Unntak kan komme...
                                    </li>
                                    <li>Du har nødt til å følge Norsk lovverk, ingen unntak vil forekomme.
                                    </li>
                                    <li>Du har ikke lov å dele seksuelt innhold</li>
                                </ol>
                            </li>
                        </ol>
                        <h3>3. Din profil</h3>
                        <ol>
                            <li>Du har selv ansvaret for hva du velger å legge ut på din profil, men det må på ingen
                                måte stride med det norske lovverk. Du må heller ikke bryte følgende regler:
                                <ul class="regler">

                                    <li>Se &sect;2. Kommunikasjon</li>
                                </ul>
                            </li>
                        </ol>
                        <h3>4. Feil i spillet/mistet verdier?</h3>
                        <ol>
                            <li>Du er pliktig til å informere Ledelsen om du finner feil i spillet.
                            </li>
                            <li>Mister du verdier, skal det rapporteres til Ledelsen. Når du rapporterer skal du
                                beskrive så grundig du kan akkurat hva du gjorde og hva du mistet. Om du ikke husker
                                det, så kan Ledelsen prøve å finne det ut for deg.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div id="info">
                <h2>Informasjon</h2>
                <p>Når du logger inn eller registrerer deg hos oss vil en del informasjon bli sendt inn til oss.
                    Følgende informasjon vil vi bli å motta:<br></p>
                <ol>
                    <li>Nettleser:</li>
                    <ul class="regler">
                        <li>Nettleseren(Chrome, Firefox, Opera, etc.)</li>
                    </ul>
                    <li>Igjennom bruk av vår nettside:</li>
                    <ul class="regler">
                        <li>Aktivitet</li>
                        <li>Forskjellige ip-adresser som blir brukt logges</li>
                    </ul>
                </ol>
                <p>Vi bruker denne informasjonen vil bli brukt for å forbedre din opplevelse av spillet, slik at vi på
                    best mulig måte kan gjøre spillet bedre for nettopp deg, og alle andre som spiller spillet. Om du
                    har
                    spørsmål, så kan du ta kontakt, se denne siden: <a
                        href="#kontakt"
                        title="Kontaktsiden">Kontakt</a></p>
            </div>
            <div id="kontakt">
                <h2>Kontakt</h2>
                <h1>E-post:</h1>
                <p style="cursor: pointer"
                   title="Klikk for å vise mail url."
                   onclick="this.title = '';
                       this.innerHTML = '<a href=\'mailto:<?= HENVEND_MAIL; ?>\'><?= HENVEND_MAIL; ?></a>'"><?= HENVEND_MAIL_SAFE; ?></p>

                <div class="cleanify"></div>
            </div>
        </div>
    </div>
</section>
<?php
include './inc/footer.php';
?>
</body>
</html>
