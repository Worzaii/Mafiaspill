<?php
include("core.php");
startpage("Familie") 
?>
    <div id="func_help"></div><div id="func_help_a"></div>
	<br><br>
<!-- SLUTT TOP -->
<!-- Begynn script her -->
<?php
 if(isset($_GET['fam'])){
  $fam = $db->escape($_GET['fam']);
  $psql = $db->query("SELECT * FROM `familier` WHERE `id`='$fam' AND `lagtned` = '0'")or die('Feil: '.mysqli_error($db->connection_id));
  if($db->num_rows() == 0){
  echo '<p>Familien du ville vise var ikke funnet, velg heller en familie <a href="familievis.php">HER</a>.</p>';
  }
  else{
  $famprofil = $db->fetch_object();
  if($famprofil->Navn == false){
   print "<font color=\"red\">Familien finnes ikke, den kan ha blitt lagt ned eller aldri eksistert.</font>";
  } else {
    
   //Div:
   $aapen = $famprofil->apen;
   if($aapen == "1"){
     $aapenvis = "Ja";
   } else {
     $aapenvis = "Nei";
   }

   $bank = $famprofil->Bank;
   if($bank >= 0 && $bank < 100000){
    $bank_status = "<img src='imgs/bar1.png' title='0-100,000,-' alt='Bar1'>";
   } else if($bank >= 100000 && $bank < 500000){
    $bank_status = "<img src='imgs/bar2.png' title='100,000-500,000,-' alt='Bar2'>";
   } else if($bank >= 500000 && $bank < 1000000){
    $bank_status = "<img src='imgs/bar3.png' title='500,000-1,000,000,-' alt='Bar3'>";
   } else if($bank >= 1000000 && $bank < 5000000){
    $bank_status = "<img src='imgs/bar4.png' title='1,000,000-5,000,000,-' alt='Bar4'>";
   } else if($bank >= 5000000 && $bank < 10000000){
    $bank_status = "<img src='imgs/bar5.png' title='5,000,000-10,000,000,-' alt='Bar5'>";
   } else if($bank >= 10000000 && $bank < 50000000){
    $bank_status = "<img src='imgs/bar6.png' title='10,000,000-50,000,000,-' alt='Bar6'>";
   } else if($bank >= 50000000 && $bank < 100000000){ // test
    $bank_status = "<img src='imgs/bar7.png' title='50,000,000-100,000,000,-' alt='Bar7'>";
   } else if($bank >= 100000000 && $bank < 500000000){
    $bank_status = "<img src='imgs/bar8.png' title='100,000,000-500,000,000,-' alt='Bar8'>";
   } else if($bank >= 500000000 && $bank < 1000000000){
    $bank_status = "<img src='imgs/bar9.png' title='500,000,000-1,000,000,000,-' alt='Bar9'>";
   } else if($bank >= 1000000000 && $bank < 5000000000){
    $bank_status = "<img src='imgs/bar10.png' title='1,000,000,000-5,000,000,000,-' alt='Bar10'>";
   } else if($bank >= 5000000000 && $bank < 15000000000){
    $bank_status = "<img src='imgs/bar11.png' title='5,000,000,000-15,000,000,000,-' alt='Bar11'>";
   } else if($bank >= 15000000000 && $bank < 50000000000){
    $bank_status = "<img src='imgs/bar12.png' title='15,000,000,000-50,000,000,000,-' alt='Bar12'>";
   } else if($bank >= 50000000000 && $bank < 100000000000){
    $bank_status = "<img src='imgs/bar13.png' title='50,000,000,000-100,000,000,000,-' alt='Bar13'>";
   } else if($bank >= 100000000000 && $bank < 1000000000000){
    $bank_status = "<img src='imgs/bar14.png' title='100,000,000,000-1,000,000,000,000,-' alt='Bar14'>";
   } else if($bank >= 1000000000000){
    $bank_status = "<img src='imgs/bar15.png' title='1,000,000,000,000+++' alt='Bar15'>";
   } else {
     $bank_status = "- error -";
   }
   $id = $famprofil->id;
   $famled = user($famprofil->Leder);
   $famub = user($famprofil->Ub);
   $opprettet = date("H:i:s | d-m-y",$famprofil->TimeMade);
   echo <<<ENDHTML
   <form method="post" action="">
   <div class="profile_section wh_34">
     <dl>
     <center><img height="300" width="300" src="$famprofil->img" alt=""></center>

     <dt>Navn:</dt>
     <dd>$famprofil->Navn</dd>

     <dt>Startet:</dt>
     <dd>$opprettet</dd>

     <dt>Boss:</dt>
	 <dd>$famled </dd>
     
     <dt>Under boss:</dt>
     <dd>$famub</dd>

     <dt>Bank-status:</dt>
     <dd>$bank_status</dd>

     <dt>&aring;pen:</dt>
     <dd>$aapenvis</dd>

     </dl>
     </form>
   </div>
ENDHTML;
   }
   }//Familie eksisterer?
   echo '</br>';
$hent = $db->query("SELECT * FROM `familier` WHERE `Navn` = '$fam' AND `lagtned` = '0'")or die('Feil: '.mysqli_error($db->connection_id));
while($row = mysqli_fetch_assoc($hent)){
$id = $row['id'];
$famprofilf = $row["profil"];
}
echo bbcodes($famprofilf,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
echo '<br><br><p style="text-align:center;">Medlemmer:</p>';
$familiemedlemmer = $db->query("SELECT * FROM `users` WHERE `family` = '".$id."'")or die('Feil: '.mysqli_error($db->connection_id));
if($db->num_rows() >= 1){
$string = null;
while($r = mysqli_fetch_object($familiemedlemmer)){
$string.= user($r->id).", ";
}
$string = substr($string, 0, -2);
echo '<p style="text-align:center;">'.$string.'</p>';
}
else{
echo '<p>Ingen medlemmer!</p>';
}
   }//Viser fam?
 else{
   //Ingen familie valg &aring; vise
 $srr = $db->query("SELECT * FROM `familier` WHERE `lagtned` = '0' ORDER BY `TimeMade` DESC") or die("Feil: ".mysqli_error($db->connection_id));
 echo '
 <div class="familie w500">
 <h1 class="big">Familieoversikt</h1>
 <table class="table center">
 ';
 if($db->num_rows() == 0){
 echo '<tr><td>Ingen familier eksisterer enda.</td></tr>';
 }
 else{
 while($r = mysqli_fetch_object($srr)){
 if($stopwhile==1){
 /*Ikke gjenta headers*/
 }
 else{
 $stopwhile=1;
 echo '
 <thead>
   <tr class="c_1">
       <td colspan="3">Familie</td>
       <td>Boss</td>
	   <td>Underboss</td>
 </tr>
 </thead>
 <tbody>
 ';
 }
 
 echo '

         <tr class="c_2">
          <td><span style="font-size: 18px;">#'.$r->id.'</span></td>
          <td><a href="familievis.php?fam='.$r->id.'"><img src="'.$r->img.'" alt="" height="35" width="35"></a></td>
          <td><a href="familievis.php?fam='.$r->id.'">'.$r->Navn.'</a></td>
          <td><a href="profil.php?id='.$r->id.'">'.user($r->Leder).'</a></td>
          <td><a href="profil.php?id='.$r->id.'">'.user($r->Ub).'</a></td>
         </tr>
';
    echo '</tbody>';
 }
 }
 
echo '</table>';
echo '</div>';
 }
endpage();