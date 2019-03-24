<?php
include("core.php");
startpage("Forumpanel");
?>
<?php
if($obj->status == 3 || $obj->status == 2 || $obj->status == 1){ ?>
<h1>Forumpanelet</h1>
<ul class="adminpanel">
	<a class="menu" href="tomprat.php" onclick="return confirm('Sikker p&aring; at du vil t&oslash;mme praten?')"><li>Rens forumet</li></a>
	<a class="menu" href="forumban.php"><li>Forumban spiller</li></a>	
</ul>
<?php }
else{startpage("Ingen tilgang!");
echo '<h1>Du har ikke tilgang til denne siden!</h1><b><br><center><p>Du har ikke tilgang til denne siden!</p></center></b>';
}
endpage();

?>

