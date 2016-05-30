  /*Sist oppdatert 03.08*/
  var waittime=1000;
  var xmlhttp = false;
  document.getElementById("praten").innerHTML = "<center><b>Laster inn chatten...</b></center>";

function ajax_read(url) {
  if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
    if(xmlhttp.overrideMimeType){
      xmlhttp.overrideMimeType('text/xml');
    }
  } else if(window.ActiveXObject){
      try{
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
      } catch(e) {
        try{
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e){}
      }
  }

  if(!xmlhttp) {
    return false;
  }

  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState==4) {
      document.getElementById("praten").innerHTML = xmlhttp.responseText;

      tiden = new Date();
      ms = tiden.getTime();
      intUpdate = setTimeout("ajax_read('prat.php')", waittime);
    }
  }

  xmlhttp.open('GET',url,true);
  xmlhttp.send(null);
}

/* Request for Writing the Message */
function ajax_write(url){
	if(window.XMLHttpRequest){
		xmlhttp2=new XMLHttpRequest();
		if(xmlhttp2.overrideMimeType){
			xmlhttp2.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			xmlhttp2=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!xmlhttp2) {
		return false;
	}

	xmlhttp2.open('GET',url,true);
	xmlhttp2.send(null);
}

function chat(){
	msg = document.getElementById("txt").value;
	msg = encodeURIComponent(msg);
	document.getElementById("txt").value = "";
	ajax_write("prat.php?write=" + msg);
}

var intUpdate = setTimeout("ajax_read('prat.php')", waittime);
