<!DOCTYPE html>
<html lang="no">
<head>
    <link rel="stylesheet"
          type="text/css"
          href="css/style.css">
    <link rel="shortcut icon"
          href="./favicon.ico">
    <meta http-equiv="content-type"
          content="text/html; charset=UTF-8">
    <title><?= /** @var string $title */
        $title; ?></title>
    <script type="text/javascript" src="./js/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="./js/teller.js"></script>
</head>
<body>
<section class="newsection"></section>
<div id="navbar_top">
    <div class="content">
        <nav>
            <ul>
                <li><a href="profil.php?id=<?= $obj->id; ?>">Profil</a></li>
                <!--<li><a href="innboks.php">Innboks</a></li>-->
                <li><a href="nyheter.php">Nyheter</a></li>
                <!--<li><a href="fengsel.php">Fengsel<?= $anyjail; ?></a></li>
                <li><a href="bj.php">BlackJack</a></li>-->
                <li><a href="online.php">Spillere pålogget (<?= $late_online; ?>)</a></li>
            </ul>
        </nav>
    </div>
</div>
<div id="information">
    <p>Spillet har blitt oppdatert. CTRL + F5</p>
</div>
<?php /** @var string $chat */
/*$chat;*/ ?>
<noscript><p>å spille uten javascript aktivert vil vise seg å være fungere dårlig, vennligst aktiver javascript eller
        bruk en nettleser som støtter dette.</p>
</noscript>
<section class="over_wrapper">
    <div class="wrapper">
        <div id="content">
            <div id="shadow">
            </div>
            <div id="leftmenu">
