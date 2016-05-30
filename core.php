<?php
/*Dette ble brukt av Nicholas den 19.12.2013 for oppdatering av sikkerhet og funksjon for modkill og 
 * andre oppdateringer av funksjoner som tilhørte.
 * if(!isset($_COOKIE["maintenance"]) && $_COOKIE["maintenance"] == 0){
  die("Mafia-no.net er under behandling, kom igjen senere!");
}*/
define("BASEPATH",1);
if(isset($_SERVER['X-Requested-With'])){
  if($_SERVER['X-Requested-With'] == "XMLHttpRequest"){
    define("JSON",1);
  }
   else {
    define("JSON", 0);
  }
}
else{
  define("JSON", 0);
}
if(defined("LVL") && LVL == TRUE){
  $r = '../';
}
else {$r = NULL;}
include_once $r.'system/config.php';
  include_once($r."classes/class.php");//Databaseklasse
  if(isset($_SESSION['sessionzar']))
  {//Spiller er aktiv via session
      $db = new database;//Åpner klassen
      $db->configure();//Starter mysqli-tilkobling
      if(!$db->connect()){
        die("Kunne ikke koble til db!"
          . "<br /><a href=\"loggut.php?g=2\">Tilbake til index.</a>");
      }
      list($user,$pass,$sss) = $_SESSION['sessionzar'];
      $ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'].$_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR'];
      //Sjekker om bruker eksisterer(modifisert session)
      $db->query("SELECT * FROM `users` WHERE `user` = '".$db->escape($user)."' AND `pass` = '".$db->escape($pass)."'");
      if($db->num_rows() == 0)
      {//Bruker med det passordet eksisterer ikke, logger ut session
        header("Location: loggut.php?g=4");
        die('<a href="loggut.php">Det kan se ut som du har blitt logget ut, det er noen andre som har logget på din bruker.</a>');
      }
      else if($db->num_rows() == 1)
      {
        $obj = $db->fetch_object();/*Brukerdataobjektet, brukes i alle script for å hente brukerinformasjon*/
        //if($obj->status > 2)die('<p style="color:#f00">Spillet er for &oslash;yeblikket utilgjengelig pga en feil. Kom innom senere.</p>');
        //$set = unserialize($obj->settings);
        /*$wea = unserialize($obj->weapon);/*Kommer opp error her(error at offset 0 of 1 bytes)
        for($i=0;$i<=9;$i++){
          if($wea[$i][1][1]){
            $activeweapon = $i;
            break;
          }
        }
        if($activeweapon == NULL){
          $activeweapon=0;
        }
        */
        if($obj->ip != $ip){
          header("Location: loggut.php?g=7&$ip");
          echo '<h1>Det kan se ut som du har blitt logget inn på et annet nettverk. Klikk her for å gå til innloggingssiden: <a href="loggut.php">Index</a></h1>';
          die();
        }
        include_once($r."inc/functions.php");//Spillfunksjoner
        
        modkill_check();/*Sjekker om spilleren er modkillet, om han er blir en fil inkludert og stopper videre script som er under*/
        liv_check();/*Sjekker livet på spilleren*/
        ipbanned($ip);/*Sjekker om IP-adressen er sperret serverside*/
        if($obj->forceout == 1)
          {
              /*Om en admin har aktivert dette
              * alternativet vil spilleren logges ut neste gang spilleren gjør noe.
              * Dermed blir spilleren utlogget med umiddelbar virkning
               */
              $db->query("UPDATE `users` SET `forceout` = '0' WHERE `id` = '{$obj->id}'"); /*Resetter force*/
              die('<a href="loggut.php?g=6">Du har blitt logget ut av en i Ledelsen! Vennligst logg inn på nytt for å fortsette å spille.</a>');
          }
          //Sjekker om spiller fortsatt er online
          if(($obj->lastactive + 1800) < time())
          {//Spilleren er inaktiv over 30 min
             header("Location: loggut.php?g=5");
          }
          else if(($obj->lastactive + 1800) > time())
          {
            if(defined("NOUPDATE") && NOUPDATE == 1){
              /*Slik at intervalscript ikke oppdaterer brukertid og holder bruker innlogget*/
            }
            else{
              if(!$db->query("UPDATE `users` SET `lastactive` = '".time()."',`ip` = '$ip' WHERE `id` = '{$obj->id}'"))
              {
                if($obj->status == 1)
                {
                  die('<p>Kunne ikke sette ny info!<br>'.mysqli_error($db->connection_id).'</p>');
                }
                else
                {
                  die('<p>Det har oppstått en feil i scriptet!!!</p>');
                }
              }
            }
          }
      }
  }
  else
  {
    header("Location: loggut.php?g=1");//Logger ut spilleren, deretter sender til index.
  }