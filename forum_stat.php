<?php
include("core.php");
include("pagination.php");
startpage("Forum-mod stats");
// Scriptet
$query = $db->query("SELECT * FROM `users` WHERE `status` = '3'");
if($db->num_rows($query) == 0){
    echo '<p class="feil">Ingen forum-moderatorer har gjort noe som helst..</p>';
}else{
 ?>
<form action="" method="post">
    <table class="table">
        <th>Statistikk over forum moderatorene</th>
        <tr><td>Nick</td><td>Tr&aring;der slettet</td><td>Sist slettet</td><td>T&oslash;mt prat</td><td>Sist t&oslash;mt</td></tr>
        <tr>
            <?php
              $sql = "SELECT * FROM `forum_stat` ORDER BY `id` DESC";
              $pagination = new Pagination($db,$sql, 20,'p');
              $pagination_links = $pagination->GetPageLinks();
              $forumstat = $pagination->GetSQLRows();
              foreach($forumstat as $forum){
                  echo '<tr><td>'.user($forum['uid']).'</td><td>'.$forum[''].'</td></tr>';
              }
            ?>
        </tr>
    </table>
</form>
<?php
}
//End
endpage();
?>