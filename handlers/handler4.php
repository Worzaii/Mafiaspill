<?php
include("../core.php");
if(isset($_POST['hideshow']) && $_POST['hideshow'] == "hideit"){
	$_SESSION['hideshow'] = false;/*Gjømmer den*/
}
?>