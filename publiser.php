<?php
include("core.php");
if(r1() || r2()){
if($obj->status == 1 || $obj->status == 2){
startpage("Publiser ny nyhet");
?>
<h1>Legg inn ny nyhet</h1>
<div style="margin:0 auto;text-align:center;width:90%;">
<?php if(isset($_POST['melding'])){
$tema = $db->escape($_POST['tema']);
$mel = $db->escape($_POST['melding']);
$dato = date("H:i:s d.m.Y");
$lvl = $db->escape($_POST['rangerank']);
if($lvl == 4){
  $lvl = '6';
}
if(strlen($mel) <= 2){
echo '<p>Meldingen er for kort!</p>';
}
else{
if(!$db->query("INSERT INTO `news`(`title`,`text`,`author`,`time`,`vis`,`userlevel`) VALUES('$tema','$mel','$obj->user','".time()."','1','$lvl')")){
if($obj->status == 1){
echo '<p>Feil: '.mysqli_error($db->connection_id).'</p>';
sysmel(2,'<b>--Nyheter</b></br>En feil skjedde under posting av nyheter!');
}
}
else{
echo '<p>Du har lagt ut en nyhet! :)</p>';
if($lvl == 1 || $lvl == 2 || $lvl == 3){
  
}
else{
  $db->query("INSERT INTO `chat` (`id`, `uid`, `mld`, `time`) VALUES (NULL, '0', 'En ny nyhet ble skrevet', UNIX_TIMESTAMP());");
}
}
}
}
?>
<form method="post" action="">
<p>Tema:<input type="text" name="tema" value="" /></p>
<p>Hvem kan se nyheten?</p>
<input id='rang' type="range" name="rangerank" min="1" max="4" value="4"><br /><span id="rankrange">Alle</span>
<p>Melding:</p>
<textarea name="melding" style="width:100%;height:250px;"></textarea>
<input type="submit" value="Publiser nyheten!" />
</form>
</div>
<script type="text/javascript">
  $("#rang").change(function(){
    var $sp = $("#rankrange");
    
    vis = new Array("<span style='color:#f00'>Administrator</span>","<span style='color:#f00'>Administrator</span>, <span style='color:#5151ff'>Moderator</span>","<span style='color:#f00'>Administrator</span>, <span style='color:#5151ff'>Moderator</span>, <span style='color:#0f0'>Forum-moderator</span>, Picmaker","Alle");
    var val = $("#rang").val();
    $sp.html(vis[val-1]);
  });
</script>
<?php
}
else{
startpage("Ingen tilgang!");
echo '<h1>Ingen tilgang!</h1>';
}
}
else{
  startpage("Ingen tilgang");
  noaccess();
}
endpage();
?>