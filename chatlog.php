<?php
include("core.php");
if(!r1()){
    startpage("Chatlog");
    echo '<h1>Ingen Tilgang</h1>';
}
else{
startpage("Chatlog");
/*$innhold = file_get_contents("chatlog/chat_00.01.56_05.11.2012.txt"); // Metode for å vise en spesifikk vil
print $innhold;*/


//include("chatlog/chat_21.32.49_05.04.2013.html"); // Annen metode for å vise en spesifikk fil

if ($handle = opendir('chatlog/')) {

    /* Korrekt måte. */
    while (false !== ($entry = readdir($handle))) {
        echo '<a style="width:10px;" href="chatlog/'.$entry.'">'.$entry.'</a><br>';
    }

    /* Feil. */
    //while ($entry = readdir($handle)) {
      //  echo "$entry\n";
    }

    closedir($handle);
}
	
echo '</br>Den nederste filen er alltid den nyeste filen!';
	endpage();
?>