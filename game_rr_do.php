<?php
die();
    include("core.php");
    $s = $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id'");
    if($db->num_rows() == 1){
        $f = $db->fetch_object();
        $ogamecode = $f->gamecode;
}

    if ($ogamecode == ".$db->escape($_GET['code']).") {
    startpage("Russisk Rulett");
	}
	  else{
    startpage("Feil kode");
    echo "Koden er feil!";
	endpage();
	}
?>
<h1>Russisk rulett</h1>


<?php 
$result = mt_rand(1,4); 
$newcode = mt_rand(100000000,999999999); 

if($result == 1) {
echo "du overlevde og fikk 10,000,000 Kr!";
   $sql = $db->query("UPDATE `users` SET  `hand` =  'hand' + '10000000', `gamecode` = '$newcode' WHERE  `users`.`id` ='$obj->id';");
      }
else {
echo "Beklager du døde!";
   $sql = $db->query("UPDATE `users` SET  `health` =  '100' WHERE  `users`.`id` ='$obj->id';");
   }
?>

	
<?php endpage();