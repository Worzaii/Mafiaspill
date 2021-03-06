<?php
include("core.php");
startpage("Ledelsen");
$getcrewcount = $db->query("SELECT count(*) FROM `users` WHERE `status` IN('1','2') ORDER BY `status` ASC, `id` ASC");
if ($getcrewcount->fetchColumn() >= 1) {
    $getcrew = $db->query("SELECT id,user,status,(unix_timestamp() - lastactive) as last FROM `users` WHERE `status` IN('1','2') ORDER BY `status` ASC, `id` ASC");
    $crewcontent = "";
    while ($r = $getcrew->fetchObject()) {
        if ($r->status == 1) {
            $st = '<span class="stat1">Admin</span>';
        } elseif ($r->status == 2) {
            $st = '<span class="stat2">Moderator</span>';
        }
        $user = user($r->id);
        $crewcontent .= <<<HTML
      <tr>
          <td>$user <b>(</b>$st<b>)</b></td>
              <td><span id="cuser$r->id"></span><script>teller($r->last,"cuser$r->id",false,"opp");</script></td>
      </tr>
HTML;
    }
} else {
    $crewcontent .= '<tr><td colspan="2"><em>Fant ingen i ledelsen</em></td></tr>';
}

$forummodsnum = $db->query("SELECT count(*) FROM `users` WHERE `status` = '3' ORDER BY `id` ASC");
if ($forummodsnum->fetchColumn() >= 1) {
    $forummods = $db->query("SELECT id,user,status, (unix_timestamp() - lastactive) as last FROM `users` WHERE `status` = '3' ORDER BY `id` ASC");
    $forumcontent = "";
    while ($r = $forummods->fetchObject()) {
        $st = '<span class="stat3">Forum Moderator</span>';
        $user = user($r->id);
        $forumcontent .= <<<HTML
      <tr>
          <td>$user <b>(</b>$st<b>)</b></td>
              <td><span id="fuser$r->id"></span><script>teller($r->last,"fuser$r->id",false,"opp");</script></td>
      </tr>
HTML;
    }
} else {
    $forumcontent .= '<tr><td colspan="2"><em>Fant ingen forum-moderatorer</em></td></tr>';
}

$piccount = $db->query("SELECT count(*) FROM `users` WHERE `status` = '4' ORDER BY `id` ASC");
if ($piccount->fetchColumn() >= 1) {
    $picmk = $db->query("SELECT id,user,status,(unix_timestamp() - lastactive) as last FROM `users` WHERE `status` = '4' ORDER BY `id` ASC");
    $piccontent = "";
    while ($r = $picmk->fetchObject()) {
        $user = user($r->id);
        $piccontent .= <<<HTML
    <tr>
        <td>$user <b>(</b><span class="stat4">Picmaker</span><b>)</b></td>
            <td><span id="puser$r->id"></span><script>teller($r->last,"puser$r->id",false,"opp");</script></td>
    </tr>
HTML;
    }
} else {
    $piccontent .= '<tr><td colspan="2"><em>Fant ingen picmakere...</em></td></tr>';
}
$supportscount = $db->query("SELECT COUNT(*) FROM `users` WHERE `support` = '1' ORDER BY `status` ASC, `id` ASC");
if ($supportscount->fetchColumn() >= 1) {
    $supports = $db->query("SELECT id,user,status,(unix_timestamp() - lastactive) as last FROM `users` WHERE `support` = '1' ORDER BY `status` ASC, `id` ASC");
    $supportcontent = "";
    while ($r = $supports->fetchObject()) {
        if ($r->status == 1) {
            $st = '<span class="stat1">Admin</span>';
        } elseif ($r->status == 2) {
            $st = '<span class="stat2">Moderator</span>';
        } elseif ($r->status == 3) {
            $st = '<span class="stat3">Forum-moderator</span>';
        } elseif ($r->status == 6) {
            $st = '<span class="stat4">Picmaker</span>';
        } else {
            $st = '<span style="color:#DEB86C;font-weight:bold;">Supportspiller</span>';
        }
        $user = user($r->id);
        $supportcontent .= <<<HTML
    <tr>
        <td>$user  <b>(</b>$st<b>)</b></td>
        <td><span id="suser$r->id"></span><script>teller($r->last,"suser$r->id",false,"opp");</script></td>
    </tr>
HTML;
    }
} else {
    $supportcontent .= '<tr><td colspan="2"><em>Fant ingen supportspillere</em></td></tr>';
}
?>
    <h1>Ledelsen</h1>
    <table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
        <tr>
            <th>Administrator / Moderator</th>
            <th>Sist aktiv:</th>
        </tr>
        <?= $crewcontent ?>
    </table>
    <br>
    <table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
        <tr>
            <th colspan="2">Utnevnte Forum mods:</th>
        </tr>
        <?= $forumcontent ?>
    </table>
    <br>
    <table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
        <tr>
            <th colspan="2">Utnevnte Picmakere:</th>
        </tr>
        <?= $piccontent ?>
    </table>
    <table class="table" style="margin-top: 1px; text-align: center; width: 540px;">
        <tr>
            <th colspan="2">Utnevnte Supportspillere:</th>
        </tr>
        <?= $supportcontent ?>
    </table>

<?php
endpage();
