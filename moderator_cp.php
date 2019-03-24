<?php
include("core.php");
startpage("Modpanel",$style);
if($obj->status <= 2){
?>

<style type="text/css">
  ul a li{
    padding-bottom: 5px;
  }
</style>
<h1>Moderator panel</h1>
<p style="text-align:center">Mod-funksjoner</p>
<ul class="adminpanel">
<a href="ipban.php"><li>Internet Protocol Adresse blokkering :D</li></a>
<a href="bankalle.php"><li>Bankoverf&oslash;ringer</li></a>
<a href="publiser.php"><li>Legge til en nyhet</li></a>
<a href="/Multizone"><li>Multi-muligheter</li></a>
<a href="modkill2.php"><li>Modkill spiller</li></a>
<a href="modkilletvis.php"><li>Se alle som er modkillet</li></a>
<a href="edityourself.php"><li>Endre egne verdier</li></a>
<a href="poenglogg.php"><li>Poeng-logg</li></a>
<a href="auksjonlogg.php"><li>Auksjonslogg</li></a>
<a href="faq_panel.php"><li>FAQ Panel</li></a>
</ul>
<p style="text-align:center"> Forummod funksjoner</p>
<ul class="adminpanel">
<a href="forumban.php"><li>Forumban spiller</li></a>
<a href="tomprat.php" onclick="return confirm('Sikker p&aring; at du vil t&oslash;mme prat? ')"><li>T&oslash;m chatten</li></a>
<a href="visprat.php"><li>Vis hele prat databasen</li></a>
</ul>
<?php }
else{
echo '<h1>Du har ikke tilgang til denne siden!</h1><b><br><center><p>Du har ikke tilgang til denne siden!</p></center></b>';
}
endpage();
?>

