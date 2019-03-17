<?php

include("core.php");

if($obj->status == 1 || $obj->status == 2){

startpage("Unban alle spillere (Forum)")

?>



<?php

if(mysql_query("TRUNCATE TABLE `forumban`")){

echo'<h1>Alle spillerne er nå unbannet.</h1><p class="lykket">Alle brukere unbannet</p>';

}

?>



<?php

}

endpage();

?>