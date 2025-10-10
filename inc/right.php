<?php
global $obj;
global $db;
if ($obj->image == null || $obj->image == '') {
    $img = "imgs/nopic.png";
} else {
    $img = $obj->image;
}
?>
<h2><?php echo $obj->user; ?></h2>
<a href="profil.php?id=<?php echo $obj->id; ?>"><img id="avatar" src="<?php echo $img; ?>" alt=""></a>
<?php
if (r1() || r2() || r3() || $obj->support == 1) {
    echo '<h2>Panels</h2><ul>';
    echo '<li><a href="panel.php" title="Your panels can be found here">Your panels</a></li>';

    if (r1() || r2() || support()) {
        echo '<li><a href="supportpanel.php">Supportcenter</a></li>';
    }
    echo '</ul>';
}
?>
<h2><?php echo htmlentities($obj->user); ?></h2>
<ul>
    <li>Rank: <?php echo $obj->rank->getRank(); ?></li>
    <li>Money: <?php echo number_format($obj->hand); ?>kr</li>
    <li>Points: <?php echo number_format($obj->points); ?></li>
    <li>City: <?php echo city($obj->city); ?></li>
    <li>HP: <?php echo $obj->health; ?>%<br>
        <div class="left_div">
            <div nonce="health"
                 style="height:4px;background: #0f0;width:<?php echo $obj->health ?>%;"></div>
        </div>
    </li>
    <li>Alliance: <?php echo ($obj->family == null) ? "<i>none</i>" :
            '<a href="#">' . famidtoname($obj->family, 1) . '</a>'; ?></li>
    <li>Ammo: <?php echo number_format($obj->bullets); ?>
    <li>Weapon: <?php echo weapon($obj->weapon) ?></li>
</ul>
<h2>Overview</h2>
<ul>
    <!--<li><a href="#">FAQ</a></li>
    <li><a href="#">BB-koder</a></li>-->
    <li><a href="/minside.php">My page</a></li>
    <!--<li><a href="/statistikk.php">Statistikk</a></li>
    <li><a href="/Ranstikk">Ran statistikk</a></li>-->
</ul>
<h2>Settings</h2>
<ul>
    <!--<li><a href="endre.php">Endre innstillinger</a></li>
    <li><a href="finnspiller.php">Finn spiller</a></li>
    <li><a href="endreprofil.php">Endre profil</a></li>
    <li><a href="nypassord.php">Endre passord</a></li>-->
    <li><a href="logout.php" onclick="return confirm('Confirm logging out?')">Log out</a></li>
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
