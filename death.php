<html>
<head>
<title>D&oslash;d spiller</title>
<link rel="stylesheet" href="css/login.css">
</head>
<body>
<header>
<div id="header"></div>
</header>
<section>
	<div class="wrapper">
		<p><b>Spilleren har blitt drept!</b></p>
		<p>En spiller drepte brukeren din. Om du &oslash;nsker &aring; starte p&aring; nytt m&aring; du <a href="http://mafia-no.net">registrere deg p&aring; nytt!</a></p>
		<?php
		if($obj->moddet == 1){
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