//Dette scriptet skal håndtere behandlingen av info frem og tilbake og forårsake autoupdate
function updatepage(str,responsediv){
    //document.getElementById(responsediv).style.display="block";
    //$cur = '#'+document.getElementById(responsediv);
    var rese = "#"+responsediv;
    $(rese).fadeIn(1000);
    document.getElementById(responsediv).innerHTML=str;
    document.getElementById(responsediv).style.display="block";
    document.getElementById(responsediv).style.color="red";
    //alert("Oppgave fullført!");
}
function redir(){
    window.location.replace("/Nyheter");
}
$(document).ready(function(){
  $("#log").submit(function(event) {
      event.preventDefault();
      var $form = $(this).serialize(),link=$(this).attr('action');
      link +="?log";
      $.ajax({
        url: link,
        data: $form,
        dataType: "json",
        type: "POST"
      })
      .done(function(data){
        $res = eval(data);
          if($res.state==1){
            updatepage($res.string,"res1");
            var count = 1;
            countdown = setInterval(function(){
            if (count === 0) {
              window.location.href="/Nyheter";
            }
            count--;
            }, 500);
          }
          else if($res.state==0){
            updatepage($res.string,"res1");
          }
      });
  });
  $("#gpw").submit(function(event) {
      event.preventDefault();
      var $form = $(this).serialize(),link=$(this).attr('action');
      link +="?gpw";
      $.ajax({
        url: link,
        data: $form,
        dataType: "json",
        type: "POST"
      }).done(function(data){
        $res = eval(data);
        updatepage($res.string,"res3");
      });
      /*var $form = $(this);
      $.post("handler.php",{});*/
  });
  $("#getaccess").submit(function(event) {
    event.preventDefault();
    var $form = $(this).serialize(),link=$(this).attr('action');
    link +="?getaccess";
    $.ajax({
      url: link,
      data: $form,
      dataType: "json",
      type: "POST"
    }).done(function(data){
      $res = eval(data);
      updatepage($res.string,"ressu");
    });
	});
  $("#brukerreg").submit(function(event) {
      event.preventDefault();
      var $form = $(this).serialize(),link=$(this).attr('action');
      $.ajax({
        url: link,
        data: $form,
        dataType: "json",
        type: "POST"
      }).done(function(data){
        $res = eval(data);
        updatepage($res.string,"ressu");
      });
  });
  $("#respas").submit(function(event) {
      event.preventDefault();
      var $form = $(this).serialize(),link=$(this).attr('action');
      $.ajax({
        url: link,
        data: $form,
        dataType: "json",
        type: "POST",
        success: function(data){
          $res = eval(data);
          updatepage($res.string,"ressu");
        }
      });
  });
});