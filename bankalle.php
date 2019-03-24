<?php
  include("core.php");
  include("pagination.php");
  $style=NULL;
  if($obj->status == 1 || $obj->status == 2){
  startpage("Viser alle bankoverf&oslash;ringer",$style);
?>
<h1>Alle bankoverf&oslash;ringer</h1>
<?php
  echo '
  <table class="table" style="width:500px;">
  <tr>
  <th colspan="5">Viser siste 100 bankoverf&oslash;ringer</th>
  </tr>
  <tr>
  <th>Id</th><th>Fra</th><th>Til</th><th>Sum:</th><th style="width:101px">Tid:</th>
  </tr>';
  $sql = "SELECT * FROM `bankoverforinger`  ORDER BY `id` DESC";
    $pagination = new Pagination($db,$sql, 50,'p');
    $pagination_links = $pagination->GetPageLinks();
    $bank = $pagination->GetSQLRows();
    foreach($bank as $tall){
    echo '
    <tr>
    <td style="font-size:20px;">#'.$tall['id'].'<td>'.status($tall['uid'],1).'</td><td>'.status($tall['tid'],1).'</td><td>'.number_format($tall['sum']).'kr</td><td>'.date("H:i:s d.m.y",$tall['time']).'</td>
    </tr>
    ';
  }
  echo '<tr><td colspan="5">'.$pagination_links.'</tr></td>';
  echo '</table>';
    }
    else{
      startpage("Ingen tilgang!");
      echo '<h1>Ingen tilgang!</h1><p>Du har ingen tilgang til &aring; vise denne siden.</p>';
    }
    endpage();