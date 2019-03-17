function teller(tid, span, redir, oppned){
  skriv = '';
  id = document.getElementById(span);
  /*dager = Math.floor(((tid * 60) * 60) * 24);*/
  timer = Math.floor((tid / 60) / 60);
  if(timer <= 0){
  min = Math.floor(tid / 60);
  }
  else{
  tidny = tid - ((timer * 60) * 60);
  min = Math.floor(tidny / 60);
  sec = tidny - (min * 60);
  }
  if (timer != 0){
    skriv = timer;
    if (timer > 1) {          
    skriv = skriv + ' timer ';       
    }
    else{         
    skriv = skriv + ' time ';
    }
	if(min != 0){
	skriv = skriv + ' og '; 
	}
    }
  if (min != 0){
    skriv = skriv + min;
    if (min > 1) {          
    skriv = skriv + ' minutter ';       
    }
    else{         
    skriv = skriv + ' minutt ';
    }
    }
    if(sec != 0){
	  if (min != 0){
        skriv = skriv + ' og '; 
        }
    skriv = skriv + sec;
    if (sec > 1) {          
      skriv = skriv + ' sekunder ';
      }
      else {     
        skriv = skriv + ' sekund ';     
        }
        }
      id.innerHTML = skriv;
      if(tid <= 0 && oppned == 'ned'){
        if (redir) {            
          location.href = self.location;
          }
          else { 
            id.innerHTML = '0 sek';    
            }
            }
            else{
              if(oppned == 'ned'){
                tid = tid - 1;
                }
                else { tid = tid + 1;}
                setTimeout('teller(' + tid + ',"' + span + '",' + redir + ',"' + oppned + '");', 1000);
                }
                }