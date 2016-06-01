<html>
<head>
<title>Modkillet spiller</title>
<link rel="stylesheet" href="css/login.css">
</head>
<body>
<header>
<div id="header"></div>
</header>
<section>
<?php
//global $obj;
echo 'Du er modkillet av:'.$obj->modav .'</br>';
echo 'Grunn:'.$obj->modgrunn;
?>
	<div class="wrapper">
		<p><b>Brukerkontoen er modkillet!</b></p>
		<p>En i Ledelsen har modkillet brukeren, ta kontakt om du mener dette er feil. <a href="mailto:support@mafia-no.net">support@mafia-no.net</a></p>
	</div>
</section>
</body>
</html>
<?php
die();