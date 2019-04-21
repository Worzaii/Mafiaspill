<?php
include("core.php");
if (r1() || r2()) {
    startpage("IP-ban bruker");
  /* IP BANN SYSTEMET */
    if (isset($_POST['ban'])) {
        $sql1 = $db->query("SELECT * FROM `ipban` WHERE `ip`='" . $db->escape($_POST[ip]) . "'");
        if (mysqli_num_rows($sql1) == 1) {
            lykket("IP-adressen er allerede bannet!");
        } else {
            $ip = $db->escape($_POST['ip']);
            $grunn = $db->escape($_POST['grunn']);
            $ips = $db->escape($_POST['ip']);
            $db->query("INSERT INTO `ipban` SET `dato`='" . time() . "', `grunn`='$grunn', `ip`='$ips',`av`='$obj->id'");
            if ($db->affected_rows() == 1) {
                echo "IP-adressen <u>" . $ip . "</u> er bannet!";
            } else {
                echo '<p>Kunne ikke banne ip-adresse: ' . $ip . '</p>';
            }
        }
    }
    ?>
<h1>Utesteng IP-adresse</h1>
<form action="" method="post">
  <table class="table" style="width:300px">
    <thead>
      <th colspan="2" style="text-align: center">IP-adresse</th>
    </thead>
      <tr class="uhead">
          <td>IP-adresse:</td>
      <td><input required="" class="frelst" type="text" name="ip" class="input"></td>
      </tr>
      <tr class="ehead">
          <td>Grunn:</td>
      <td><textarea required="" class="frelst" name="grunn" style="width: 225px;height: 200px;"></textarea>
      </tr>
      <tr class="uhead">
          <td colspan="2"><input type="submit" name="ban" value="Ban IP!" class="submit"></td>
      </tr>
  </table>
</form>
<table class="table">
  <thead>
    <tr>
      <th colspan="4">Bannede IP-adresser</th>
    </tr>
    <tr>
      <th>Adressen:</th><th>Grunnlaget:</th><th>Av:</th><th>Dato:</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i = $db->query("SELECT * FROM `ipban` ORDER BY `id` DESC");
    while ($r = mysqli_fetch_object($i)) {
        echo '<tr><td>' . $r->ip . '</td><td>' . $r->grunn . '</td><td>' . user($r->av) . '</td><td>' . date("H:i:s d.m.Y", $r->dato) . '</td></tr>';
    }
    ?>
  </tbody>
</table>
    <?php
} else {
    startpage("Ingen tilgang");
    noaccess();
}
endpage();