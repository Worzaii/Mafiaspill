<?php
include("core.php");
startpage("Fabrikk");
/*
    @author Slowboii
    @date 29.04.14 */
if($obj->har_fabrikk == 0){
    //Har ikke fabrikk
    echo 'I n&aelig;rmeste tid s&aring; vil du snart f&aring; kj&oslash;pe fabrikk.</br>
        Her kommer det; </br>
        * Bank for fabrikker <font style="color:red;">*IKKE P&aring;BEGYNT*</font></br>
        * Produksjon av hylser / krutt for produksjon av kuler <font style="color:red;">*IKKE P&aring;BEGYNT*</font></br>
        * Kj&oslash;p / Salg av hylser / krutt slik at kulefabrikk-eier kan kj&oslash;pe de <font style="color:yellow;">*PROGRESS*</font></br> 
        * Kj&oslash;p / Salg av r&aring;varer som trengs for &aring; produsere hylser/krutt. <font style="color:green;">*DONE*</font></br>
      </br>
        Forventet ferdig: 01.07.2014 </br>
        Datoen kan endres uten varsel.</br>
        </br>
        Sp&oslash;rsm&aring;l rettes til <a href="http://mafia-no.net/profil.php?id=2">Slowboii</a>
        ';
}elseif($obj->har_fabrikk == 1){
    // Har fabrikk
    $fabrikk_type = array(
        1=>array('navn' => "Hylse Fabrikk"),
        2=>array('navn' => "Krutt Fabrikk"),
    );
    $oppskrift = array(
        1=>array('ingr_name' => "Salpeter",'html' => "sal", 'need' => 90,'pris' => 500000, 'type' => 2),
        2=>array('ingr_name' => "Svovel", 'html' => "svo", 'need' => 30,'pris' => 200000, 'type' => 2),
        3=>array('ingr_name' => "Trekull", 'html' => "tre", 'need' => 30,'pris' => 200000, 'type' => 2),
        4=>array('ingr_name' => "Kopper", 'html' => "kop", 'need' => 50,'pris' => 500000, 'type' => 1),
        5=>array('ingr_name' => "St&aring;l", 'html' => "stal",'need' => 50,'pris' => 300000, 'type' => 1) ,
    );
    $kapp = array("sal"=>1,"svo"=>2,"tre"=>3,"kop"=>4,"stal"=>5);

    //TODO: KJ�P / SELG AV KRUTT OG HYLSER
    // TODO: KJ�P / SALG AV INGREDIENSER
    // TODO: BANK FOR FABRIKK
    $page = $_GET['page'];
    if($page == 1){
     // Bank
        header("Location: /fabrikk.php");
    }elseif($page == 2){
        // KJ�P / SELG AV KRUTT OG HYLSER 
        ?>

<?php
    }elseif($page == 3){
        // KJ�P R&aring;VARER
        $curr = unserialize($obj->fabrikk_ingredienser);
        $total = 0;
        foreach($curr as $type => $ant){
            $total += $ant;
        }
        echo "Du har $total / ".$obj->fabrikk_oppgradert;
        if(isset($_POST['send'])){
            // Sett variablene.
           /*BUG:  $salpeter = intval($_POST['salpeter']);
            $svovel = intval($_POST['svovel']);
            $trekull = intval($_POST['trekull']);*/
            $radioinput = $_POST['radioInput'];
            //var_dump($radioinput);
            $textfield = (is_numeric($db->escape($_POST['inputA']))) ? $db->escape($_POST['inputA']) : 0;
            if($curr == NULL){
                echo 'Du fucket spillet v&aring;rt. Thanks. Please let us know.';
                endpage();
                die();
            }
            if($radioinput == 'sal' || $radioinput == 'svo' || $radioinput == 'tre' || $radioinput == 'kop' || $radioinput == 'stal'){
                $total = 0;
                foreach($curr as $type => $ant){
                    $total += $ant;
                }
                if(($curr[$radioinput] < $obj->fabrikk_oppgradert) && ($textfield <= ($obj->fabrikk_oppgradert - $total))){
                    if($textfield >= 1){
                        if($obj->hand >= $oppskrift[$radioinput]["pris"]){
                            $curr[$radioinput] +=$textfield;
lykket('Grattis makker, du kj&oslash;pte deg '.htmlentities($textfield).' stk '.$oppskrift[$kapp[$radioinput]]["ingr_name"].' til prisen av '.number_format($oppskrift[$kapp[$radioinput]]['pris'] * $textfield).'!');
                            $db->query("UPDATE `users` SET `fabrikk_ingredienser` = '".serialize($curr)."',`hand` = (`hand` - ".($oppskrift[$kapp[$radioinput]]['pris'] * $textfield).") WHERE `id` = '{$obj->id}' LIMIT 1");
                        }
                        else{
                            feil('Du har ikke r&aring;d til &aring; kj&oslash;pe '.htmlentities($textfield).' stk '.$oppskrift[$radioinput]["ingr_name"].'! Se at du har nok penger p&aring; handa til &aring; betale '.number_format(($oppskrift[$kapp[$radioinput]]['pris'] * $textfield)).' kr!');
                        }
                    }
                    else{
                        feil('Du kan ikke kj&oslash;pe 0 stk '.$oppskrift[$kapp[$radioinput]]["ingr_name"].', velg et antall mellom 1 og '.($obj->fabrikk_oppgradert - $curr[$radioinput]).'');
                    }
                }
                else{
                    feil('Du kan ikke kj&oslash;pe s&aring; mye, klikk <a href="/Poeng">her</a> for &aring; oppgradere fabrikken!');
                }
            }else{
                feil('Du har ikke valgt godkjent ingrediens, pr&oslash;v igjen!');
            }
        }
        else if(isset($_POST['selg'])){
            /*Mye koder, GG!*/
            $radioinput = $_POST['radioInput'];
            //var_dump($radioinput);
            $textfield = (is_numeric($db->escape($_POST['inputA']))) ? $db->escape($_POST['inputA']) : 0;
            if($curr == NULL){
                echo 'Du fucket spillet v&aring;rt. Thanks. Please let us know.';
                endpage();
                die();
            }
            if($radioinput == 'sal' || $radioinput == 'svo' || $radioinput == 'tre' || $radioinput == 'kop' || $radioinput == 'stal'){
                if($textfield <= $curr[$radioinput]){
                    if($textfield >= 1){
                        $curr[$radioinput] -=$textfield;
lykket('Grattis makker, du solgte '.htmlentities($textfield).' stk '.$oppskrift[$kapp[$radioinput]]["ingr_name"].' for prisen av '.number_format($oppskrift[$kapp[$radioinput]]['pris'] * $textfield).'!');
                        $db->query("UPDATE `users` SET `fabrikk_ingredienser` = '".serialize($curr)."',`hand` = (`hand` + ".($oppskrift[$kapp[$radioinput]]['pris'] * $textfield).") WHERE `id` = '{$obj->id}' LIMIT 1");
                    }
                    else{
                        feil('Du kan ikke selge 0 stk '.$oppskrift[$kapp[$radioinput]]["ingr_name"].', velg et antall mellom 1 og '.($curr[$radioinput]).'');
                    }
                }
                else{
                    feil('Du kan ikke selge mer enn du allerede har!');
                }
            }else{
                feil('Du har ikke valgt godkjent ingrediens, pr&oslash;v igjen!');
            }
        }
        ?>
<form action="" method="post">
    <table style="width:320px;" class="table">
        <th colspan="4">Kj&oslash;p R&aring;varer</th>
        <tr>
            <td>Hva</td>
            <td>Pris</td>
            <td>Antall</td>
            <td>Velg</td>
        </tr>
        <?php
            foreach($oppskrift as $val => $valen){
            if($valen['type'] === 2 && $obj->fabrikk_type == 2){
                echo '<tr>
                    <td>'.$valen['ingr_name'].'</td>
                    <td>'.number_format($valen['pris']).' kr</td>
                    <td>'.$curr[$valen['html']].'</td>
                    <td><input type="radio" name="radioInput" value="'.$valen['html'].'"></td>
                    </tr>';
            }elseif($valen['type'] === 1 && $obj->fabrikk_type == 1){
                echo '<tr>
                    <td>'.$valen['ingr_name'].'</td>
                    <td>'.number_format($valen['pris']).' kr</td>
                    <td>'.$curr[$valen['html']].'</td>
                    <td><input type="radio" name="radioInput" value="'.$valen['html'].'"></td>
                    </tr>';
            }
            ?>
        <?php
        }
        ?>
        <tr>
            <td colspan="4">
                <input type="submit" name="send" value="Kj&oslash;p">
                <input type="submit" name="selg" value="Selg">
            </td>
        </tr>
    </table>
</form>            
            <?php
    }
    elseif($page == 4){
        // Produksjon av hylser/krutt
        /*Her bruker vi oppskriften for &aring; lage hylser eller krutt av r&aring;varene vi har kj&oslash;pt tidligere.*/
        $unser = unserialize($obj->fabrikk_ingredienser);
        $type = array(
            1=>array('name' => "hylser"),
            2=>array('name' => "krutt")
        );
        $query = $db->query("SELECT * FROM `users` WHERE `id` = '$obj->id'");
        if(isset($_POST['produser'])){
            //Gj&oslash;r noe da bruker trykker produser
        }
        ?>
<form action="" method="post">
    <table style="width:390px;" class="table">
        <th colspan="3">Produser <?=$type[$obj->fabrikk_type]['name']?></th>
        <?php
        while($r = mysqli_fetch_object($query)){
            // Loop
            if($r->fabrikk_type == 1){
            echo '
                <tr>
                    <td>Kopper</td>
                    <td>'.$unser['kop'].'</td>
                    <td><input type="text" name="kopper"></td>
                </tr>
                <tr>
                    <td>St&aring;l</td>
                    <td>'.$unser['stal'].'</td>
                    <td><input type="text" name="stal"></td>
                </tr>
                ';
            }elseif($r->fabrikk_type == 2){
             echo '
                <tr>
                    <td>Salpeter</td>
                    <td>'.$unser['sal'].'</td>
                    <td><input type="text" name="salpeter"></td>
                </tr>
                <tr>
                    <td>Trekull</td>
                    <td>'.$unser['tre'].'</td>
                    <td><input type="text" name="trekull"></td>
                </tr>
                <tr>
                    <td>Svovel</td>
                    <td>'.$unser['svo'].'</td>
                    <td><input type="text" name="svovel"></td>
                </tr>
                ';               
            }
        }
        echo '<td><input type="submit" name="produser" value="Produser"></td>';
                ?>
    </table>
</form>
<?php
    }
}
endpage();

?>