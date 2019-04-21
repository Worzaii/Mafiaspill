<?php
global $obj;
global $db;
if ($obj->image == null || $obj->image == '') {
    $img = "imgs/nopic.png";
} else {
    $img = $obj->image;
}
$rank = rank($obj->exp);
?>
<h2><?= $obj->user; ?></h2>
<a href="profil.php?id=<?= $obj->id; ?>"><img id="avatar" src="<?= $img; ?>" alt=""></a>
<?php
if ($obj->status <= 3 || $obj->status == 6 || $obj->support == 1) {
    echo '<h2>Panel</h2><ul>';
    if ($obj->status == 1) {
        echo '
  <li><a href="fulladmin.php">Administrer spillet</a></li>
  <li><a href="adminjobb.php">Logg for admins</a></li>
  ';
    }
    if ($obj->status <= 2) {
        echo '
      <li><a href="moderator_cp.php">Moderator panel</a></li>
      <li><a href="nyforum.php?type=4">Ledelsens forum</a></li>
      ';
    }
    if ($obj->status <= 3) {
        echo '
      <li><a href="forum_cp.php">Forumadministrasjon</a></li>
      ';
    }

    if ($obj->support == '1' || $obj->status == '1' || $obj->status == '2') {
        echo '<li><a href="supportpanel.php">Supportsenter (ute av drift)</a></li>';
    }
    echo '</ul>';
}
?>
<h2>Din Bruker</h2>
<ul>
    <li>Rank: <?= $rank[1]; ?></li>
    <li>Penger: <?= number_format($obj->hand); ?>kr</li>
    <li>Poeng: <?= number_format($obj->points); ?></li>
    <li>By: <?= city($obj->city); ?></li>
    <li>Liv: <?= $obj->health; ?>%<br><div style="width:100px;height:4px;background: #f00;"><div style="width:<?= $obj->health ?>%;height:4px;background: #0f0;"></div></div></li>
    <li>Familie: <?php
        if ($obj->family == null) {
            echo "<i>ingen</i>";
        } else {
            echo '<a href="#">' . famidtoname($obj->family, 1) . '</a>';
        }
        ?></li>
    <li>Kuler: <?= number_format($obj->bullets); ?>
    <li>V&aring;pen: <?= weapon($obj->weapon) ?></li>
</ul>
<h2>Oversikt</h2>
<ul>
    <li><a href="/FAQ">FAQ</a></li>
    <li><a href="Koder">BB-koder</a></li>
    <li><a href="minside.php">Min side</a></li>
    <li><a href="statistikk.php">Statistikk</a></li>
    <li><a href="Ranstikk">Ran statistikk</a></li>
</ul>
<h2>Innstillinger</h2>
<ul>
    <li><a href="endre.php">Endre innstillinger</a></li>
    <li><a href="finnspiller.php">Finn spiller</a></li>
    <li><a href="endreprofil.php">Endre profil</a></li>
    <li><a href="/Passord">Endre passord</a></li>
    <li><a href="loggut.php" onclick="return confirm('Er du sikker p&aring; at du vil logge ut? ')">Logg ut</a></li>
</ul>
</div>
</div>
</div>
</section>
<?php
include_once './inc/footer.php';
?>
<script>
    $(document).ready(function () {
        $(document).bind("click", function (event) {
            $("div.custom-menu").remove();
        });
        $("a.user_menu").bind("contextmenu", function (event) {
            event.preventDefault();
            var thevalue = event.delegateTarget.attributes[1].value;
            var split = thevalue.split(";");
            var user = split[0];
            var id = split[1];
            $("body")
                    .append("<div class=\"custom-menu\"><ul><li><a href=\"profil.php?id=" + id + "\">G&aring; til Profil</a></li><li><a href=\"innboks.php?ny&usertoo=" + user + "\">Send melding</a></li><li><a href=\"bank.php?til=" + user + "\">Send penger</a></li></ul></div>");
            $("div.custom-menu").css({top: event.pageY + "px", left: event.pageX + "px"});
        });
    });
</script>
</body>
</html>