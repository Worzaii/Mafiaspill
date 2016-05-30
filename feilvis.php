<?php
include("core.php");
startpage("feil.txt");
if(r1()){ ?>
<h1>Viser innholdet i feil.txt</h1>
<p><a href="feiltom.php" onclick="return confirm('Er du sikker på at du ønsker å tømme feil.txt? \nHvis du velger å fjerne dette så vil ikke du kunne se tidligere feil lengre!');">Klikk her for å tømme feil.txt</a></p>
<?php
$f = fopen("feil.txt","r");
echo '<textarea style="width:100%;height:600px">'.utf8_decode(print_r(fread($f,filesize("feil.txt")),true)).'</textarea>';
?>
<?php }
else{
   noaccess();
}
endpage();