<?php
    session_name("Mafia-no");
    session_start();
    header('Content-Type: text/html; charset=UTF-8');
    ini_set("date.timezone","Europe/Oslo");//Norsk tid
    define('THRUTT', "Sperredørp!");
    include("classes/class.php");
    $db = new database;
    include("inc/functions.php");
    if(isset($_SESSION['sessionzar'])){//Bruker innlogget, sjekker for feil
      $db->configure();
      $db->connect()or$db->connection_error();
      list($user,$pass) = $_SESSION['sessionzar'];
      $sql = $db->query("SELECT * FROM `users` WHERE `user` = '$user' AND `pass` = '$pass'")or $db->query_error();//Sjekker om bruker er registrert med bruker og pass
      $obj = $db->fetch_object();/*Brukerdataobjektet, brukes i alle script for å hente brukerinformasjon*/
      unset($user,$pass);
      if($db->num_rows() <= 0){
        //Bruker med det passordet eksisterer ikke, logger ut session
        die('<h1><a href="loggut.php">Din bruker eksisterer ikke, og derfor kan du ikke lese chatten!</a></h1>');
      }
      else if($db->num_rows() == 1){
        if($obj->activated == 0){
          die('<h1><a href="loggut.php">Din bruker er ikke registrert, og derfor kan du ikke lese chatten!</a></h1>');
        }
        //Sjekker om spiller fortsatt er online
        if(($obj->lastactive + 1800) < time()){//Spilleren er inaktiv over 30 min
        /*if(isset($_COOKIE['always'])){//Oppdaterer session slik at spiller kan fortsette uforstyrret
        if(!mysql_query("UPDATE `users` SET `lastactive` = '$time',`ip` = '$ip',`lastdato` = '$date' WHERE `id` = '{$obj->id}'")){
        if($obj->status == 1){
        die('<p>Kunne ikke oppdatere brukerinformasjon! '.mysql_error().'</p>');
        }
        else{
        die('<p>Det er feil ved brukerinformasjonsoppdatering! Vennligst send email til
        <a href="mailto:system@mafia-no.net">system@mafia-no.net</a> angående denne feilen.</p>');
        }
        }
        }
        else{*/
          //header("Location: loggut.php");
          die("Din bruker har v&aelig;rt inaktiv i lengre enn 30 minutter. Logg inn for &aring; fortsette;");/*
        }*/
        }
      }
    }
    else{
      //header("Location: loggut.php");
      die("Ingen bruker aktiv, chat kan ikke leses!");
    }
    function tid($i){
      $d = explode(" ",$i);
      return ($d[0]);
    }
    if(isset($_GET['write'])){
      if($_GET['write'] == 'uptime'){
        $uptime = system(uptime);
        $time = time() + 1;
        $db->query("INSERT INTO `chat`(`id`,`uid`,`mld`,`time`) VALUES (NULL,'0','$uptime','$time')");
      }
      if($obj->health == 0){
        //Ikke gjør noe ettersom personen er død.
        $_SESSION['chatwarning'] = array('string'=>'<p style="color:#f00;">En d&oslash;d mann kan vell ikke snakke?</p>','time'=>(time() + 10));
      }
      else{
        $time = time();
        $s = $db->query("SELECT * FROM `forumban` WHERE `uid` = '$obj->id' AND `timeleft` > '$time' AND `act` = '1' ORDER BY `timeleft` DESC LIMIT 1");
        if($db->num_rows($s) == 1){
          /* Ikke gjør noe, ettersom personen er bannet! */
          $_SESSION['chatwarning'] = array('string'=>'<p style="color:#f00;">Du har ikke tillatelse til &aring; skrive, pr&oslash;v igjen senere.</p>','time'=>(time() + 10));
        }
        else{
          $w = $db->escape($_GET['write']);
          if(strlen($w) <= 1){
            $time = time() + 5;
            $_SESSION['chatwarning'] = array('string'=>'<p style="color:#f00;">Du m&aring; skrive 2 tegn eller mer for &aring; bruke chatten!</p>','time'=>$time);
          }
          else{
            $db->query("INSERT INTO `chat` VALUES(NULL,'$obj->id','$w','".time()."')");
            header("Content-Type: application/json");
            echo json_encode(array("s"=>1));
            $ikkevis=true;
          }
        }
      }
    }
    if($ikkevis==false){
      $chat = $db->query("SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 0,30");
      if(isset($_SESSION['chatwarning'])){
        if(($_SESSION['chatwarning']['time'] - time()) >= 0){
          echo $_SESSION['chatwarning']['string'];
        }
        else{
          unset($_SESSION['chatwarning']);
        }
      }
      while($r = mysqli_fetch_object($chat)){
          $teksten = $r->mld;
          $teksten = htmlentities($teksten, ENT_NOQUOTES, 'UTF-8');
          $uob = user($r->uid,1);
          if($uob->status == 1){
            $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
          }
          else if($uob->status == 2 || $uob->status == 6){
            $teksten = bbcodes($teksten, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
          }
          else{$teksten = bbcodes($teksten, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0);}
          if($r->uid == 0){
            $uob = "Systemet";
          }
          else{
            $uob = '<a href="profil.php?id='.$uob->id.'">'.$uob->user.'</a>';
          }
          if($r->id % 2){
              echo
              '<div class="chat ct1"><b>['.date("H:i:s d.m.y",$r->time).']</b> &lt;'.$uob.'&gt;: <span class="chattext">'.$teksten.'</span></div>';
          }
          else{
              echo
              '<div class="chat ct2"><b>['.date("H:i:s d.m.y",$r->time).']</b> &lt;'.$uob.'&gt;: <span class="chattext">'.$teksten.'</span></div>';
          }
      }
    }
    $db->disconnect();