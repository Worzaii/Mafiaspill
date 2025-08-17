<?php
global $obj;
global $db;
if ($obj->image == null || $obj->image == '') {
    $img = "imgs/nopic.png";
} else {
    $img = $obj->image;
}
?>
<h2><?= $obj->user; ?></h2>
<a href="profil.php?id=<?= $obj->id; ?>"><img id="avatar" src="<?= $img; ?>" alt=""></a>
<?php
if (r1() || r2() || r3() || $obj->support == 1) {
    echo '<h2>Paneler</h2><ul>';
    echo '<li><a href="panel.php" title="Paneler du har tilgjengelige vises her">Dine paneler</a></li>';

    /*if (r1() || r2() || support()) {
        echo '<li><a href="supportpanel.php">Supportsenter</a></li>';
    }*/
    echo '</ul>';
}
?>
<h2><?= htmlentities($obj->user); ?></h2>
<ul>
    <li>Rank: <?= $obj->rank->getRank(); ?></li>
    <li>Hånd: <?= number_format($obj->hand); ?>kr</li>
    <li>Poeng: <?= number_format($obj->points); ?></li>
    <li>By: <?= city($obj->city); ?></li>
    <li>Liv: <?= $obj->health; ?>%<br>
        <div class="left_div">
            <div nonce="health"
                 style="height:4px;background: #0f0;width:<?= $obj->health ?>%;"></div>
        </div>
    </li>
    <li>Familie: <?= ($obj->family == null) ? "<i>ingen</i>" :
            '<a href="#">' . famidtoname($obj->family, 1) . '</a>'; ?></li>
    <li>Kuler: <?= number_format($obj->bullets); ?>
    <li>Våpen: <?= weapon($obj->weapon) ?></li>
</ul>
<h2>Oversikt</h2>
<ul>
    <!--<li><a href="#">FAQ</a></li>
    <li><a href="#">BB-koder</a></li>-->
    <li><a href="/minside.php">Min side</a></li>
    <!--<li><a href="/statistikk.php">Statistikk</a></li>
    <li><a href="/Ranstikk">Ran statistikk</a></li>-->
</ul>
<h2>Innstillinger</h2>
<ul>
    <!--<li><a href="endre.php">Endre innstillinger</a></li>
    <li><a href="finnspiller.php">Finn spiller</a></li>
    <li><a href="endreprofil.php">Endre profil</a></li>
    <li><a href="nypassord.php">Endre passord</a></li>-->
    <li><a href="loggut.php" onclick="return confirm('Er du sikker på at du vil logge ut? ')">Logg
            ut</a></li>
</ul>
</div>
</div>
</div>
</section>
<?php
include_once './inc/footer.php';
?>
</body>
</html>