/*
 *Copyright Nicholas Arnesen 2014
 *Dette er beskyttet av opphavsrett, og oppdages det brukt p&aring; andre nettsider vil det medf&oslash;lge konsekvenser
 *Scriptet ble designet for bruk p&aring; http://mafia-no.net
 *For &aring; kunne bruke dette scriptet en annen plass, ta kontakt med overnevnt p&aring; mail: nicholas@mafia-no.net
 **/
function teller(tid, span, redir, oppned)
{
    skriv = '';
    id = document.getElementById(span);
    week = Math.floor(tid / 604800);/*Antall uker inaktiv*/
    day = Math.floor((tid - (week * 604800)) / 86400);/*Antall dager inaktiv*/
    tim = Math.floor((tid - ((week * 604800) + (day * 86400))) / 3600);
    min = Math.floor((tid - ((week * 604800) + (day * 86400) + (tim * 3600))) / 60);
    sec = Math.floor(tid - ((week * 604800) + (day * 86400) + (tim * 3600) + (min * 60)));
    if (week != 0) {
        skriv = week;
        if (week > 1) {
            skriv = skriv + ' u';
        } else {
            skriv = skriv + ' u';
        }
    }
    if (day != 0) {
        if (week != 0) {
            skriv = skriv + ", " + day;
        } else {
            skriv = day;
        }
        if (day > 1) {
            skriv = skriv + ' d, ';
        } else {
            skriv = skriv + ' d, ';
        }
    }
    if (tim != 0) {
        if (day != 0) {
            skriv = skriv + tim;
        } else {
            skriv = tim;
        }
        if (tim > 1) {
            skriv = skriv + ' t ';
        } else {
            skriv = skriv + ' t ';
        }
    }
    if (min != 0) {
        if (tim != 0) {
            if (sec != 0) {
                skriv = skriv + ', ';
            } else {
                skriv = skriv + ' og ';
            }
        }
        skriv = skriv + min;
        if (min > 1) {
            skriv = skriv + ' m ';
        } else {
            skriv = skriv + ' m ';
        }
    }
    if (sec != 0) {
        if (min != 0) {
            skriv = skriv + ' og ';
        }
        skriv = skriv + sec;
        if (sec > 1) {
            skriv = skriv + ' s ';
        } else {
            skriv = skriv + ' s ';
        }
    }
    id.innerHTML = skriv/*+ "<br>Debug:<br>Tid: "+tid +"<br>Uker: " + week + "<br>Dager: " + day + "<br>Timer: "+ tim + "<br>Minutter: "+ min + "<br>Sekunder: "+sec+"<br>"*/;
    if (tid <= 0 && oppned == 'ned') {
        if (redir) {
            location.href = self.location;
        } else {
            id.innerHTML = '0 sek';
        }
    } else {
        if (oppned == 'ned') {
            tid = tid - 1;
        } else {
            tid = tid + 1;
        }
        setTimeout('teller(' + tid + ',"' + span + '",' + redir + ',"' + oppned + '");', 1000);
    }
}