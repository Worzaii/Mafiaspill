<?php
session_name("Mafia-no");
session_start();
header('Content-type: application/json');
if(isset($_GET['fned'])){
    if(isset($_POST['grunn'])){/*Legger ned familien*/
        include_once("../classes/class.php");
        $db = new database;
        $db->configure();
        $db->connect();
        list($u,$p)=$_SESSION['sessionzar'];
        $s2 = $db->query("SELECT * FROM `users` WHERE `user` = '$u' AND `pass` = '$p' LIMIT 1");
        $obj = $db->fetch_object();
        if($db->num_rows() == 1){
            $s = $db->query("SELECT * FROM `familier` WHERE `leader` = '{$obj->id}' AND `alive` = '1' AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
            if($db->num_rows() == 1){
                $s2 = $db->fetch_object();
                if($db->query("UPDATE `familier` SET `active` = '0' WHERE `id` = '{$s2->id}'")){
                    $str = array('text'=>"Familien din har blitt lagt ned!","redir"=>"Familie");
                }
                else{
                    $str = array("text"=>"Kunne ikke legge ned familie, feil med db eller query!");
                }
                
            }
            else{
                $str = array('text'=>"Du har ikke familie &aring; slette!");
            }
            //$str = array('text'=>"Denne funksjonen er ikke klar, men den jobbes med atm!");
        }
        else{
            $str = array("text"=>"Du er ikke logget inn, da brukeren ikke eksisterer. Logg inn &aring; pr&oslash;v p&aring; nytt!");
        }
    }
    else{
        $str = array("text"=>"Du m&aring; skrive inne en grunn for nedleggelse!");
    }
    if(empty($str)){
        $str = array("text"=>"Ingen data postet!");
    }
}
$str = json_encode($str);
    print $str;
?>
