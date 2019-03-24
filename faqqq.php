<?php
// Ikke jobbet med denne enn&aring;.
include("core.php");
function br($t){
  return str_replace("\n","<br>",$t);
}
startpage("FAQ - Frequently Asked Questions");
// FAQ
$sporring = $db->query("SELECT * FROM `faq`");
?>
<div class="tema_wrap"><div class="faq_1">
    <?php
while($r = mysqli_fetch_object($sporring)){
    echo br($r->innhold,0,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1);
}
?>
    </div></div>
<?php
endpage();
?>