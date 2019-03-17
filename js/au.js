/*Autooppdatering for spiller*/

jQuery.fn.exists = function(){return this.length>0;};
var optinv = 15 * 1000;
var lastid = null;
$(document).ready(function(){
  var inte = setInterval(function(){
    $.ajax("handlers/handler6.php",{
      dataType: "json",
      data:{id: uniqid},
      statusCode: {
        404: function(){
          clearInterval(inte);
        },
        500: function(){
          clearInterval(inte);
        }
      },
      success: function(data){
        var res=eval(data);
        if(res.lastid == lastid){
          /*Oppdaterer ikke*/
        }
        else{
          if(res.show === 1){
            if($("#aud").exists()){
              $("#aud").fadeOut(500).delay(3000).remove();
            }
            $("#rightmenu").prepend('<div id="aud" style="display:none;margin: 1px; color: rgb(255, 255, 255); background: rgba(41, 41, 41, 0.74);text-align: center;line-height: 23px;padding-top: 6px;padding-bottom: 6px;"></div>');
            $("#aud").html(res.txt).fadeIn(500);
            lastid=res.lastid;
          }
          else{
            $("#aud").fadeOut(500);
          }
        }
      }
    });
  },optinv);
});