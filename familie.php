<?php
    include("core.php");
    $in = null;
    if(isset($_GET['s'])){
        //Viser innhold til en side.
        $s = $_GET['s'];
        if($s == 0){//Viser familiene, listet etter oppretting av familien.
            $title = "Familier";
            $titleh1="Viser familier";
            $s = mysql_query("SELECT * FROM `familier` WHERE `alive` = '1' AND `active` = '1' ORDER BY `id` ASC");
            $in .="<p><a href=\"Familie\">Tilbake til familiesenter!</a></p>";
            $in.='
                <table class="table">
                <tr>
                <th style="font-size:20px;">#</th>
                <th>Gjengnavn</th>
                <th>Leder</th>
                <th>Medlemmer</th>
                <th>Mer?</th>
                </tr>';
            while($r = mysql_fetch_object($s)){
                /*Henter ut informasjon og skriver ut til siden*/
                $in .= '<tr>
                    <td style="font-size:20px;text-align:center;">'.$r->id.'</td>
                    <td>'.htmlentities($r->name).'</td>
                    <td>'.user($r->leader).'</td>
                    <td>Kommer...</td>
                    <td>?</td>
                    </tr>';
            }
            $in.='</table>';
            /*Viser fra f&oslash;rste til siste der f&oslash;rste kommer f&oslash;rst.*/
        }
        else if($s==1){//Viser side for kj&oslash;p av familie
            $title="Familiekj&oslash;p";
            $titleh1="Kj&oslash;p familie";
            $in.='<div class="famnav"><p><a href="?s=0">Se alle familier.</a></p><p><a href="?s=1">Kj&oslash;p familie!</a></p></div>';
            $res = null;
            $s = mysql_query("SELECT * FROM `familier` WHERE `leader` = '{$obj->id}' AND `active` = '1' AND `alive` = '1' OR `ul` = '{$obj->id}' AND `active` = '1' AND `alive` = '1'");
            if(mysql_num_rows($s) == 1){
                $in.= '<p class="feil">Du eier eller er i familie, og kan derfor ikke kj&oslash;pe.</p>';
            }
            else{
                if(isset($_POST['fname'])){
                //CREATE TABLE `familier`(
                //`id` int not null auto_increment,
                //`name` varchar(30) not null,
                //`opd` bigint not null,
                //`leader` int not null,
                //`ul` int not null,
                //`bank` bigint not null default '10000000',
                //`alive` enum('0','1'),
                //`health` int(3) not null default '100',
                //primary key(`id`)
                //);
                    /*Pr&oslash;ver &aring; opprette familie*/
                    $na = mysql_real_escape_string($_POST['fname']);
                    if(!preg_match("/^[\w-_]+[a-zA-Z]+[\w-_ ]*$/", $na)){
                        $res = '<p class="feil">Familienavnet var ikke godkjent!</p>';
                    }
                    else{
                        if(strlen($na) <= 5){
                            $res = '<p class="feil">Familienavnet skal v&aelig;re mellom 6-30 tegn. <br>Er det mer eller mindre enn det vil du ikke kunne opprette familien!</p>';
                        }
                        else{
                            $s = mysql_query("SELECT * FROM `familier` WHERE `name` = '".htmlentities($na)."' AND `alive` = '1'");
                            if(mysql_num_rows($s) == 1){
                                //Familienavn opptatt
                                $res = '<p>Familienavnet du valgte er allerede i bruk, velg et annet. Om du er i en familie, s&aring; g&aring;r dette for det samme. Du kan ikke ha flere familier samtidig.';
                            }
                            else{
                                $s2=  mysql_query("SELECT * FROM `familier` WHERE `leader` = '{$obj->id}' AND `active` = '1' AND `alive` = '1' OR `ul` = '{$obj->id}' AND `active` = '1' AND `alive` = '1'");
                                if(mysql_num_rows($s2) == 1){
                                    $res = '<p class="feil">Du er allerede en leder i en familie, du kan ikke opprette ny familie!</p>';
                                }
                                else{
                                    //Sjekker brukerkontanter
                                    $prisfamilie = 9000000000;
                                    $maxfam = 5;
                                    $s = mysql_query("SELECT * FROM `familier` WHERE `alive` = '1' AND `active` = '1' HAVING `health` > '0' ORDER BY `id` DESC");
                                    if(mysql_num_rows($s) >= 5){
                                        $res = '<p class="feil">Det kan bare eksistere 5 familier samtidig, du kan ikke opprette familie.';
                                    }
                                    else{
                                        if($obj->hand >= $prisfamilie){
                                            
                                            //Oppretter familie
                                            if(mysql_query("INSERT INTO `familier`(`name`,`leader`) VALUES('$na','{$obj->id}')")){
                                                if(mysql_query("UPDATE `users` SET `hand` = (`hand` - $prisfamilie) WHERE `id` = '{$obj->id}' LIMIT 1")){
                                                    $res = '<p class="lykket">Du har opprettet familien '.htmlentities($na).'!<br><a href="Familie?s=2">Se familiepanelet!</a></p>';
                                                }
                                                else{
                                                    $res= '<p class="feil">Pengene for &aring; opprette familien kunne ikke bli trekt!</p>';
                                                }
                                                
                                            }
                                            else{
                                                if(r1()){
                                                    $r2="<br/>".mysql_error();
                                                }
                                                else{
                                                    $r2=null;
                                                }
                                                $res = '<p class="feil">Familien kunne ikke opprettes...'.$r2.'</p>';
                                            }
                                        }
                                        else{
                                            $res = '<p class="feil">Du har ikke r&aring;d til &aring; kj&oslash;pe en familie! Du m&aring; ha '.number_format($prisfamilie).'kr! :3';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $in = $res.'
                    <p style="color:#f00;">Advarsel: Om du velger &aring; opprette deg familie n&aring;, s&aring; vil den slettes etter hvert da scriptet er i oppdatering ofte. Du vil ikke eie familien lengre n&aring;r funksjonen er klar, for da vil det komme kostnader til kj&oslash;p av familie og begrensninger! Slowboii var her :*<br/>
                    </p>
                    <form method="post">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Opprett familie</th>
                                </tr>
                            </thead>
                            <tbody style="text-align:center;">
                                <tr>
                                    <th>Familienavn:<br>Familienavn kan kun best&aring; av f&oslash;lgende tegn: a->z A->Z 0-9 _ - * ^ og mellomrom.</th>
                                </tr>
                                <tr>
                                    <td><input type="text" name="fname" placeholder="Maks 30 tegn..."></td>
                                </tr>
                                <tr>
                                    <td><input type="submit" value="Opprett familie"></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    ';
            }
        }
        else if($s==2){
            //Familiepanelet
            $title="Familiekontroll";
            $in.= <<<END
                <script>
                    $(document).ready(function(){
                    $('#content div.swit div').hide();
                    $('#content div.swit #index').show();
                    $('div.swit ul a').click(function(){
                        var currentTab = $(this).attr('href');
                        $('#content div.swit div').fadeOut();
                        $(currentTab).fadeIn();
                        return false;
                    });
                });
            </script>
            <p><a href="Familie">Tilbake til familiesenteret!</a></p>
            <p>Her kan du kontrollere familien din:</p>
<div class="swit">
<div id="index"><p>Viser familiepaneler under:</p>
<ul>
<li><a href="#lf">Legg ned familien</a></li>
<li>Bank: Se, endre og overf&oslash;r</li>
<li>Se medlemss&oslash;knader</li>
<li>Medlemsoversikt og kontroll p&aring; medlemsrettigheter.</li>
</ul>
</div>
<div id="lf">
<form method="post" action="handlers/handler3.php" id="lfn">
    <table class="table" style="max-width:500px;">
        <tr>
            <th>Legge ned familie?</th>
        </tr>
        <tr>
            <td>Ved &aring; legge ned familien, s&aring; mister du evt. verdier som ligger inne p&aring; familiebanken, og all oversikt over medlemmer vil forsvinne ogs&aring;! 
            Er du sikker p&aring; at du vil legge ned familien?</td>
        </tr>
        <tr>
            <td><input type="text" name="grunn" placeholder="Hvorfor legge ned familie?"></td>
        </tr>
        <tr>
            <td><input type="submit" name="conf" value="Fjern familie!"></td>
        </tr>
    </table>
</form>
</div>
</div>
END;
            $titleh1='Familiepanel';
        }
    }
    else{
        $title = "Familiesenter";
        $titleh1="Familiesenter";
        $s = mysql_query("SELECT * FROM `familier` WHERE `leader` = '{$obj->id}' AND `active` = '1' AND `alive` = '1' OR `ul` = '{$obj->id}' AND `active` = '1' AND `alive` = '1'");
            if(mysql_num_rows($s) == 1){
                $senter='<p><a href="Familie?s=2">Se familiepanelet ditt!</a></p>';
            }
            else{
                $senter=null;
            }
        
        $in .='<div class="famnav"><p><a href="?s=0">Se alle familier.</a></p><p><a href="?s=1">Kj&oslash;p familie!</a></p>'.$senter.'</div>
            <div id="res1"></div>
            <p>Her kommer familiesenteret til mafia-no.net. Det jobbes med atm av Werzire. Ta kontakt om noen lurer p&aring; noe.</p>';
    }
    $e = '<script src="js/famhan.js"></script>';
    startpage($title,$e);
    echo '<h1>'.$titleh1.'</h1>'.$in;
    endpage();
?>