<?php
include("core.php");
error_reporting(E_ALL);
$res=NULL;
if(r1() || r2()){
  /*Det er en administrator, viser panel for lesning og kommentering*/
  $ex = '<link href="jui/css/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">';
  startpage("H&aring;ndter s&oslash;knader",$ex);
  echo '<h1>S&oslash;kepanel for Ledelsen</h1>';
  ?>
<p>Her vil det komme et panel som gj&oslash;r slik at Ledelsen kan se p&aring; s&oslash;knader og stemme for den de tror vil utf&oslash;re stillingen p&aring; best mulig m&aring;te, i henhold til de s&oslash;knadstekstene som er sendt.</p>
<br>
<div id="apd" style='display:none;'></div>
<br>
<table class="table">
  <thead>
    <tr>
      <th colspan="3">Viser s&oslash;knader</th>
    </tr>
    <tr>
      <th>Bruker</th><th colspan="2">Dato innsendt</th>
    </tr>
  </thead>
  <tbody>
<?php
$role = array(
    1=>"Moderator",2=>"Forum-Moderator",3=>"Support"
);
$s = $db->query("SELECT * FROM `soknads` WHERE `slettet` = '0' ORDER BY `status` ASC,`id` DESC");
if($db->num_rows() >= 1){
  /*Lister opp s&oslash;knader i tabell*/
  while($r = mysqli_fetch_object($s)){
$a = $db->query("SELECT COUNT(`res`) AS `ned` FROM `vote` WHERE `sid` = '$r->id' AND `res` = '0'");
$ar= $db->fetch_object();
$b = $db->query("SELECT COUNT(`res`) AS `opp` FROM `vote` WHERE `sid` = '$r->id' AND `res` = '1'");
$br= $db->fetch_object();
$nede = $ar->ned;
$oppe = $br->opp;
    echo '<tr><td>'.user($r->uid).'</td><td>'.date("H:i:s d.m.Y",$r->time).'</td><td onclick="javascript:toggler(\'soknad'.$r->id.'\');">Vis s&oslash;knad</td></tr>
    <tr><th colspan="4" id="soknad'.$r->id.'" style="font-weight:normal; display:none;">
      <p>';
      $opp = '<span style="color:red;">'.$nede.'</span>';
      $ned = '<span style ="color:green;">'.$oppe.'</span>';
      echo '
      <button class="button" onclick="voter('.$r->id.',1)">Stem opp</button>
      <button class="button" onclick="voter('.$r->id.',0)">Stem ned</button>
          '.$opp.' '.$ned.'
              
      </p>
      <div style="text-align:left;">Navn: '.$r->name.' '.$r->lname.'</br>
        Alder: '.$r->age.'</br>Stilling: '.$role[$r->role].'</br>
          <b></br></br>'.bbcodes($r->profile,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0).'</b></br>
            </div><p style="font-size:16px;font-weight:bold">Gi tilbakemelding p&aring; s&oslash;knaden:</p>
            <textarea style="display:block;width:90%" name="kommentar" id="kom'.$r->id.'"></textarea>
              <button class="subcom" value="kom'.$r->id.'" data="'.$r->id.'">Legg inn tilbakemelding</button>';
      $sq = $db->query("SELECT * FROM `soknadkom` WHERE `sid` = '$r->id'");
      while($q = mysqli_fetch_object($sq)){
        echo '<div style="border:1px dotted #fff">'.user($q->uid).'<br>Tid: '.date("H:i:s d.m.Y",$q->time).'<br><br>'.
          htmlentities($q->msg,NULL,"windows-1252").'</div>';
      }
      echo'</th></tr>';
  }
}
else{
  /*Gir beskjed om at ingen s&oslash;knader har blitt levert*/
  echo '<tr><td colspan="4">Ingen s&oslash;knader har blitt sendt inn, eller er ubehandlet.</td></tr>';
}
?>
  </tbody>
</table>
<script>
$(document).ready(function(){
  $(".togl").css({"cursor":"pointer","font-style":"italic","text-align":"center"});
});
function toggler(id){
    var id = "#" + id;
    $id = $(id);
    if($id.css("display") === "none"){
      $($id).fadeIn({
        duration:750
      });
    }
    else{
      $($id).fadeOut({
        duration:750
      });
    }
  }
  /*Lager funksjon for stemning*/
  function voter(sok,wayz){
    //sok er id til kommentar
    $.ajax("handlers/handler5.php",{
      data: {id: sok, way: wayz},
      dataType: "json",
      type: 'GET'
    }).done(function(data){
      resz = eval(data);
      if(resz.res === 1){
        alert(resz.txt);
      }
      else{
        alert(resz.txt); 
      }
    });
  }
  $(".subcom").on("click",function(){
  /*Henter verdi og finner text...*/
  var $com = $(this);
  var kommentar = $com.attr("value");
  console.log("Kommentaren: " + kommentar);
  var ko = "#" + kommentar;
  console.log("Insertion: " + ko);
  var resu=$(ko).val();
  console.log("Ko value:" + resu);
  var id1 = $com.attr("data");
  console.log("dataid:" + id1);
  $.ajax("handlers/handler5.php",{
      data:{kid:id1,msg:resu},
      dataType: "json",
      type: "GET"
    }).done(function(data){
      res = eval(data);
      //$("#apd").append("<p>"+res.txt+"</p>").css({"display":'block'});
      if(res.slett === 1){
        $(ko).val("");
      }
      alert(res.txt);
    }).fail(function(){
      alert("Det oppstod en feil!");
    });
  });
</script>
<?php
}
else{
  /*Viser en form der brukere kan legge inn en s&oslash;knad til oss i ledelsen.*/
  if(isset($_POST['realname']) && isset($_POST['realbname']) && isset($_POST['realage']) && isset($_POST['stilling']) && isset($_POST['sokenformore'])){
    /*Starter gjennomg&aring;ing av informasjon*/
    $n1=$db->escape($_POST['realname']);
    $n2=$db->escape($_POST['realbname']);
    $n3=$db->escape($_POST['realage']);
    $n4=$db->escape($_POST['stilling']);
    $n5=$db->escape($_POST['sokenformore']);
    if(strlen($n1) <= 2 || strlen($n2) <= 1 || !is_numeric($n3) || $n3 <= 0 || !in_array($n4, array(1,2,3)) || strlen($n5) <= 20){
      /*Noe var feil, og da sl&aring;r denne ut*/
      $res= '<p class="feil">Du har ikke fyllt ut nok informasjon!</p>';
      if(strlen($n1) <= 2){
        $res.='<p class="feil">Du har ikke fyllt inn fornavnet ditt, det m&aring; v&aelig;re minst p&aring; 3 tegn eller mer!</p>';
      }
      if(strlen($n2) <= 1){
        $res.='<p class="feil">Du har ikke fyllt inn etternavnet ditt, det m&aring; v&aelig;re minst p&aring; 3 tegn eller mer!</p>';
      }
      if(!is_numeric($n3) || $n3 <= 0){
        $res.='<p class="feil">Din alder m&aring; v&aelig;re et tall! Og over 0.</p>';
      }
      if(!in_array($n4, array(1,2,3))){
        $res.='<p class="feil">Du har ikke valgt riktig verdi ifra Stillingslista!</p>';
      }
      if(strlen($n5) <= 20){
        $res.='<p class="feil">Din s&oslash;knad er enten mangelfull eller for kort, den m&aring; minst v&aelig;re 20 tegn. Forklar hvorfor du vil v&aelig;re en del av Ledelsen!</p>';
      }
    }
    else{
      /*Setter inn i tabell*/
      $db->query("INSERT INTO `soknads` (`id`, `uid`, `name`, `lname`, `age`, `profile`,`role`, `status`,`time`) VALUES (NULL, '$obj->id', '$n1', '$n2', '$n3', '$n5','$n4', 0,UNIX_TIMESTAMP());");
      if($db->affected_rows() == 1){
        $res= '<p class="lykket">Din s&oslash;knad har blitt sendt inn! N&aring;r Ledelsen har vurdert din s&oslash;knad, s&aring; vil du motta en melding i din systeminnboks om du fikk en stilling. Men om du ikke fikk noe, vil det st&aring; en henvendelse i din innboks fra en i Ledelsen om akkurat hvorfor du ikke ble valgt.</p>';
      }
      else{
        $res= '<p class="feil">Kunne ikke lagre. Gi dette til en i Ledelsen:<br>'.mysqli_error($db->connection_id).'</p>';
      }
    }
  }
  startpage("S&oslash;k deg inn i Ledelsen...");
  ?>
<h1>Ledelsens s&oslash;kesenter</h1>
<p>Vi i Ledelsen forventer &aring;penhet og &aelig;rlighet, hvis vi f&oslash;ler at vi ikke kan stole p&aring; deg, s&aring; vil din s&oslash;knad muligens avsees. S&aring; forklar s&aring; godt som du kan i din s&oslash;knad. Legg opp for hvorfor akkurat du skal v&aelig;re den ene som kan hjelpe oss, noe som muligens de andre s&oslash;kerne ikke kan.</p>
<?=$res?>
<form method="post" action="">
  <table class="table">
    <thead>
      <tr>
        <th colspan="2">Send inn en s&oslash;knad til Ledelsen</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Fornavn:</th>
        <td><input type="text" name="realname" placeholder="Ditt navn"><br>Det m&aring; minst v&aelig;re 3 tegn</td>
      </tr>
      <tr>
        <th>Etternavn:</th>
        <td><input type="text" name="realbname" placeholder="Ditt etternavn"><br>Det m&aring; minst v&aelig;re 3 tegn</td>
      </tr>
      <tr>
        <th>Alder:</th>
        <td><input type="number" name="realage" min="15" max="30" value="15"><br>Det m&aring; v&aelig;re tall</td>
      </tr>
      <tr>
        <th>Stilling:</th>
        <td><select name="stilling">
          <option value="1">Moderator</option>
          <option value="2">Forum-moderator</option>
          <option value="3">Support-spiller</option></select>
        </td>
      </tr>
      <tr>
        <th colspan="2">Din s&oslash;knad:</th>
      </tr>
      <tr>
        <td colspan="2">S&oslash;knaden din burde v&aelig;re lengre enn 20 tegn, og inneholde informasjon du tror Ledelsen vil ha. Godt tips er &aring; "ramse opp" erfaringer du har. Pr&oslash;v ogs&aring; &aring; bruke "Enter" slik at ikke alt blir s&aring; tettsittende. Det blir da lettere for oss &aring; lese. Din s&oslash;knad vil vises for alle Moderatorer og Admins i spillet. Alle vil da gi din s&oslash;knad en vurdering fra 1-6 der 6 er best. Lykke til spillere!<br><textarea name="sokenformore" style="width: 580px;height: 400px;" placeholder="Skriv inn her hvorfor du &oslash;nsker &aring; v&aelig;re en del av Ledelsen i valgt rolle."></textarea></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center"><input type="submit" value="Send inn s&oslash;knaden!" name="submitter"></td>
      </tr>
    </tbody>
  </table>
</form>
<?php
}
endpage();