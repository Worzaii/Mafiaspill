<?php
	session_start(); // starter session
	
	$width  	= 120;	// lengden p&aring; bagrunnnnen
	$height 	=  40;	// h&oslash;yden p&aring; bakgrunnenn
	$length 	=  rand(3,7);	// en rand mellom 3 og 7 p&aring; bokstavenen/tallene
	$baseList 	= '0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // bokstaver og tall som vil komme opp p&aring; bildet
	$code    	= "";	// NB!! la denne st&aring; tom!nu
	$counter 	= 0;
	$image 		= imagecreate($width, $height) or die('Kunne ikke velge GD!'); 	// lager bildet

		// Selve antiboten

	for($i=0; $i<10; $i++){
	   imageline($image, 
			 mt_rand(0,$width), mt_rand(0,$height), 
			 mt_rand(0,$width), mt_rand(0,$height), 
			 imagecolorallocate($image, mt_rand(150,255), 
										mt_rand(150,255), 
										mt_rand(150,255)));
	}
	
	for($i=0, $x=0; $i<$length; $i++){
	   $actChar = substr($baseList, rand(0, strlen($baseList)-1), 1);
	   $x += 10 + mt_rand(0,10);
	   imagechar($image, mt_rand(3,5), $x, mt_rand(5,20), $actChar, 
		  imagecolorallocate($image, mt_rand(0,155), mt_rand(0,155), mt_rand(0,155)));
	   $code .= strtolower($actChar);
	}
	   
	header('Content-Type: image/jpeg');
	imagejpeg($image);
	imagedestroy($image);
	
	$_SESSION['securityCode'] = $code;
?> 