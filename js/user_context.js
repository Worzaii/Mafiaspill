$(document).ready(function() {
  $(document).bind('click', function(event) {
    $('div.custom-menu').remove();
  });
  $('a.user_menu').bind('contextmenu', function(event) {
    event.preventDefault();
    let id = $(this).attr('data-id');
    let user = $(this).attr('data-user');
    $('body').
    append('<div class="custom-menu"><ul><li><a href="profil.php?id=' + id +
        '">G&aring; til Profil</a></li><li><a href="innboks.php?ny&usertoo=' +
        user +
        '">Send melding</a></li><li><a href="bank.php?til=' + user +
        '">Send penger</a></li></ul></div>');
    $('div.custom-menu').
    css({top: event.pageY + 'px', left: event.pageX + 'px'});
  });
  $('#ct').on('click', function() {
    window.location.href = 'https://mafia.werzaire.net/chat.php';
  });
});