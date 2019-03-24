<?php
include 'core.php';
startpage();
?>
<h1>Tilbud</h1>
<p>Her vil det komme tilbud i ny og ne, men pass p&aring;, de kommer uten forvarsel, og hvor lenge de varer st&aring;r ogs&aring; p&aring; siden! :)
  Julekalenderen gir ut penger til dere spillere, men velger dere &aring; samle opp penger og sende til dere selv vil det bli tatt som multi. God jul!<br>Forresten, tilbud varer kun 5 dager tilbake heretter.</p>
<div class="resboks"></div>
<?php
if(date("m") == 12){
if(date("j") >= date("j") - 5){
  $ri = (date("j") - 5);
}
for ($i = $ri;$i<=24;$i++){
  echo '
<h2>'.$i.'. Desember</h2>
<p class="chodo" data="'.$i.'">Klikk her for &aring; motta en premie!</p>';
}
?>
<script>
$(document).ready(function(){
  $("p.chodo").on("click",function(){
    var val = $(this).attr("data");
    $.ajax({
      url:"handlers/handler7.php",
      data:{dag:val},
      dataType: 'JSON',
      type: 'POST',
      success: function (data) {
        var re = eval(data);
        console.log(re);
        $(".resboks").html(re.txt);
      }
    });
  });
});
</script>
<?php
}
endpage();