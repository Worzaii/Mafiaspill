<?php
    include("core.php");
    $style = '
<style>
    .selwho optgroup[label="Administratorer"] option{
        color:#ccc;
    }
    .selwho optgroup[label="Administratorer"]{
        color:red;
    }
    .selwho optgroup[label="Moderatorer"]{
        color:lime;
    }
    .selwho optgroup[label="Supportspillere"]{
        color:#999;
    }
    .selwho optgroup,.selwho optgroup option{
        background:#666;
        color:#ccc;
    }
    .selwho optgroup{
    background: #333;
    }
    select{
    background: #171717;
    color:#fff;
    border:1px solid #222;
    border-radius:10px;
    padding:10px;
    margin:5px auto;
    outline:none;
    }
    input[name="ticketn"]{
    background: #171717;
    color:#fff;
    font-weight:bold;
    border:1px solid #222;
    border-radius:10px;
    padding:10px;
    margin:5px auto;
    outline:none;
    opacity:.8;
    width:260px;
    }
    input[name="ticketn"]:focus{
    font-weight:normal;
    opacity:1;
    }
</style>
        ';
    startpage("Support",$style);
?>
<h1>Supportsiden</h1>
<div>
    <form method="post">
        <?php
            if(isset($_POST['ticketn']) && isset($_POST['tickett']) && isset($_POST['ticketi'])){
                $n = $db->escape($_POST['ticketn']);//Temaet
                $t = $db->escape($_POST['tickett']);//Type
                $i = $db->escape($_POST['ticketi']);//Meldingen
                $d = time();
                if(strlen($n) <= 3 || $t >= 4  || $t < 0 || strlen($i) <= 9){
                    /*Alle mulige feil sjekkes, dermed sjekkes
                     * hver enkelt om noen er feil og skriver ut
                     */
                    echo feil('Du har kanskje ikke fylt ut skjemaet helt:');
                    if(strlen($n) <= 3){
                        echo feil('Temaet er for kort!');
                    }
                    if($t >= 4 || $t < 0){
                        echo feil('Feil i valg!');
                    }
                    if(strlen($i) <= 9){
                        echo feil('Supportmeldingen er for kort. Det er minimum 10 tegn!');
                    }
                }
                else{
                    if($db->query("INSERT INTO `support`(usid,theme,issue,text,time) VALUES('$obj->id','$n','$t','$i','$d')")){
                      echo '
                      <p class="lykket">Din melding har blitt mottat! Du vil få svar så snart noen i support-delen av spillet ser meldingen!</p>
                      ';
                    }
                    else{
                        if($obj->status == 1){
													echo '
													<p class="feil">Det oppstod en feil!<br>' . mysqli_error($db->con) . '</p>
													';
                        }
                        else{
													echo '
													<p class="feil">Det har oppstått en feil!<br>Ta vare på dette til senere:</p>
													';
													echo '
													<p>Du skrev følgende:<br>Tema: '.$n.'<br>Din tekst: <br>'.$i.'</p>
													';
                        }
                    }
                }
            }
        ?>
<style type="text/css" media="screen"></style>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Fyll ut skjemaet med nok opplysninger</th>
                </tr>
            </thead>
            <tbody>
                <tr class="c_1">
                    <td>Tema:</td>
                    <td><input type="text" maxlength="20" name="ticketn"></td>
                </tr>
                <tr class="c_2">
                    <td>Ang.</td>
                    <td style="text-align: center">
                        <select name="tickett">
                            <option value="0">Om spillet</option>
                            <option value="1">Om funksjoner</option>
                            <option value="2">Feil i spillet</option>
                            <option value="3">Annet</option></select>
                    </td>
                </tr>
                <!--<tr class="c_1">
                    <td>Hvem skal kunne svare:</td>
                    <td style="text-align: center;">
                        <select name="who" class="selwho">
                            <optgroup label="Administratorer">
                            <?php
                                $q = $db->query("SELECT * FROM `users` WHERE `status` = '1' ORDER BY `id` ASC")or die(mysql_error());
                                while($r = mysqli_fetch_object($q)){
                                    echo '<option value="'.$r->id.'">'.$r->user.'</option>';
                                }
                            ?>
                            </optgroup>
                            <optgroup label="Moderatorer">
                            <?php
                                $q = $db->query("SELECT * FROM `users` WHERE `status` = '2' ORDER BY `id` ASC")or die(mysql_error());
                                while($r = mysqli_fetch_object($q)){
                                    echo '<option value="'.$r->id.'">'.$r->user.'</option>';
                                }
                            ?>
                            </optgroup>
                            <optgroup label="Supportspillere">
                            <?php
                                $q = $db->query("SELECT * FROM `users` WHERE `status` = '3' ORDER BY `id` ASC")or die(mysql_error());
                                while($r = mysqli_fetch_object($q)){
                                    echo '<option value="'.$r->id.'">'.$r->user.'</option>';
                                }
                            ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>-->
                <tr class="c_3">
                    <td colspan="2" style="text-align:center;">
                        <p>Skriv inn en utfyllende tekst under som beskriver så mye som mulig om det emnet du vil ha behandlet.</p>
                        <textarea name="ticketi" style="width: 440px;height: 200px;"></textarea>
                        <br>
                        <input type="submit" value="Send inn supportmelding!" class="knapp">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<?php
    endpage();
?>