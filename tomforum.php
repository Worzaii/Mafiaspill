<?php
include("core.php");
if($obj->status == 1 || $obj->status == 2){
startpage("Tøm forum")
?>

<?php
if(mysql_query("TRUNCATE TABLE `forum`")){
if(mysql_query("TRUNCATE TABLE `forumsvar`")){
echo'<p class="lykket">Forumet ble tømt!</p>';
}
}
?>

<?php
}
endpage();
?>