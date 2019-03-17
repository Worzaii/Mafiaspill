<?php
include("inc/dbc.php");
$s = mysql_query("SELECT * FROM `users` ORDER BY `id` ASC");
echo '
<p>Sjekker '.mysql_num_rows($s).' rader.</p>
';
while($r = mysql_fetch_object($s)){
if(!filter_var($r->mail, FILTER_VALIDATE_EMAIL))
  {
  echo '<p style="color:#f00;">Denne mailen er ikke godkjent! "'.$r->mail.'" på bruker: '.$r->user.'('.$r->id.')</p>';
  }
else
  {
  echo '<p>'.$r->user.'('.$r->id.') bruker en godkjent email!</p>';
  }
}
echo '<p>Ferdig å sjekke alle radene. </p>';
?>