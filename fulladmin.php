<?php
include("core.php");
startpage("Administrasjon");
?>
<style type="text/css">
  ul a li{
    padding-bottom: 5px;
  }
</style>
<?php
if($obj->status == 1){ ?>
<h1>Adminpanelet</h1>
<p style="text-align:center"> Test Funksjoner</p>
<ul class="adminpanel">
    <a href="oppdrag_ny.php"><li>Oppdrag (<font color="red">NY</font>)</li></a>
	<a><li>Mer kommer ..</li></a>
</ul>
<p style="text-align:center"> Admin funksjoner</p>
<ul class="adminpanel">
    <a href="ipban.php"><li>Internet Protocol Adresse blokkering :D</li></a>
    <a href="bankalle.php"><li>Siste bankoverf&oslash;ringer(100 siste)</li></a>
    <a href="endre_spiller.php"><li>Sjekk opp spiller(Ikke klar, arbeides med)</li></a>
    <a href="actonline.php"><li>Logg ut en spiller!</li></a>
    <a href="antinlog.php"><li>Se antall innlogginger!</li></a>
    <a href="publiser.php"><li>Legg ut ny nyhet!</li></a>
    <a href="modkill2.php"><li><em>Modkill spiller</em></li></a>
    <a href="/Multizone"><li>Multi-muligheter</li></a>
    <a href="stilling.php"><li>Sett stilling til en spiller!</li></a>
    <a href="endrespiller.php"><li>Endre spillers verdier!</li></a>
    <a href="modkilletvis.php"><li>Se alle som er modkillet</li></a>
    <a href="poenglogg.php"><li>Poenglogg</li></a>
    <a href="auksjonlogg.php"><li>Auksjonslogg</li></a>
    <?php if(in_array($obj->id, array(1,2))){echo '<a href="stillinglogg.php"><li>Stillingslogg</li></a>';}?>
</ul>
<p style="text-align: center;">Moderatorpanel</p>
<ul class="adminpanel">
                <a href="bankalle.php"><li>Bankoverf&oslash;ringer</li></a>
		<a href="publiser.php"><li>Legge til en nyhet</li></a>
		<a href="ipsjekk.php"><li>Sjekk spillere som kan bruke multi</li></a>
		<a href="modkill2.php"><li>Modkill spiller</li></a>
                <a href="poenglogg.php"><li>Poeng-logg</li></a>
                <a href="modkilletvis.php"><li>Se alle som er modkillet</li></a>
                <a href="edityourself.php"><li>Endre egne verdier</li></a>
                <a href="faq_panel.php"><li>FAQ Panel</li></a>
		
</ul>
<p style="text-align:center"> Forummod funksjoner</p>
<ul class="adminpanel">
<a href="visprat.php"><li>Vis hele prat databasen!</li></a>
<a href="forumban.php"><li>Forumban spiller</li></a>
 <a href="tomprat.php" onclick="return confirm('Er du sikker p&aring; at du vil t&oslash;mme praten?')"><li>Loggf&oslash;r og t&oslash;m praten!</li></a>
<a href="chatlog.php"><li>Vis loggf&oslash;rte chat logger</li></a>
</ul>
<br><br>
<?php }
else
{
	echo '<h1>Du har ikke tilgang til denne siden!</h1><b><br><center><p>Du har ikke tilgang til denne siden!</p></center></b>';
}
endpage();
?>