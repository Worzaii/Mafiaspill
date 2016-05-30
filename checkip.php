<?php
include 'core.php';
if(r1()){
  startpage("Bruker-IP-sjekk");
  ?>
<h1>Ip-sjekkups for brukere</h1>
<p>Sjekk opp bruk av ip-adresser fra innlogginger/sessions</p>
<form method="post" action="">
  <table class="table" style="width:50%;">
    <thead>
      <tr>
        <th colspan="2">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>IP-adresse</th><td><input type="text" style="width:60%"><input type="submit" style="margin:0;margin-left:10px;"></td>
      </tr>
    </tbody>
  </table>
</form>
<?php
  endpage();
}
else{
  startpage("Ingen tilgang");
  noaccess();
  endpage();
  die();
}