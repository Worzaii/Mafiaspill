<html>
<head>
<title>Død spiller</title>
<link rel="stylesheet" href="css/login.css">
</head>
<body>
<header>
<div id="header"></div>
</header>
<section>
    <div class="wrapper">
        <p><b>Spilleren har blitt drept!</b></p>
        <p>En spiller drepte brukeren din. Om du ønsker å starte på nytt må du <a
                    href="<?=DOMENE_NAVN;?>">registrere deg på nytt!</a></p>
        <?php
        if ($obj->moddet == 1) {
            echo <<<ENDHTML
		<p>Du har blitt modkillet av: $obj->modav<br>
		Grunn: $obj->modgrunn<br>
ENDHTML;
        }
        ?>
    </div>
</section>
</body>
</html>
<?php
die();