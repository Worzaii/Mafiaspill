function loggteller(tid, span, redir, oppned)
{
    let skriv = '';
    let id = document.getElementById(span);
    let day = Math.floor(((tid / 60) / 60) / 24);/*Antall dager inaktiv*/
    let tim = Math.floor(((tid - (60 * (60 * (day * 24)))) / 60) / 60);/*Antall timer inaktiv*/
    let min = Math.floor((tid - ((60 * (60 * (day * 24))) + ((tim * 60) * 60))) / 60);/*Antall minutter inaktiv*/
    let sec = Math.floor((tid - ((60 * (60 * (day * 24))) + ((tim * 60) * 60) + (min * 60))));/*Antall sekunder inaktiv*/
    if (day != 0) {
        skriv = day;
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
            skriv = skriv + ' og ';
        }
        skriv = skriv + min;
        if (min > 1) {
            skriv = skriv + ' min ';
        } else {
            skriv = skriv + ' min ';
        }
    }
    if (sec != 0) {
        if (min != 0) {
            skriv = skriv + ' og ';
        }
        skriv = skriv + sec;
        if (sec > 1) {
            skriv = skriv + ' sek ';
        } else {
            skriv = skriv + ' sek ';
        }
    }
    id.innerHTML = skriv;
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
        setTimeout('loggteller(' + tid + ',"' + span + '",' + redir + ',"' + oppned + '");', 1000);
    }
}