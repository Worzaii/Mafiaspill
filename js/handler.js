function updatepage(str, responsediv, fadeout = true) {
  let rese = '#' + responsediv;
  $(rese).fadeIn(1000).html(str).css({'display': 'block', 'color': 'red', 'word-break': 'break-all'});
  if (fadeout===true) {
    $(rese).delay(1000).fadeOut(1500);
  }
}


$(document).ready(function() {
  $('#loginform').submit(function(event) {
    event.preventDefault();
    $login = $('#loginfield');
    $pwd = $('#loginfield input[name=\'password\']');
    let $form = $(this).serialize(), link = $(this).attr('action');
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
      beforeSend: function() {
        $login.attr('disabled', 'disabled');
      },
      success: function(data) {
        const $res = eval(data);
        if ($res.state===1) {
          let count = 1.5;
          setInterval(function() {
            if (count < 0) {
              window.location.href = $res.href;
            }
            count -= 0.5;
          }, 500);
        }
        updatepage($res.string, 'loginresult', false);
      },
      error: function(data) {
        updatepage('Feil skjedde ved databehandling, tilbakemelding:' +
          data.responseText, 'loginresult', false);
      },
      complete: function(data) {
        setTimeout(function() {
          $login.removeAttr('disabled');
          $pwd.val('');
        }, 2000);
      },
    });
  });
  $('#forgotpasswordform').submit(function(event) {
    event.preventDefault();
    let $form = $(this).serialize(), link = $(this).attr('action');
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
    }).done(function(data) {
      let $res = eval(data);
      updatepage($res.string, 'forgotpasswordresult');
    });
  });
  $('#getaccessform').submit(function(event) {
    event.preventDefault();
    let $form = $(this).serialize(), link = $(this).attr('action');
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
    }).done(function(data) {
      let $res = eval(data);
      updatepage($res.string, 'registerresult');
    });
  });
  $('#registerform').submit(function(event) {
    event.preventDefault();
    let $form = $(this).serialize(), link = $(this).attr('action');
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
    }).done(function(data) {
      let $res = eval(data);
      updatepage($res.string, 'ressu');
    });
  });
  $('#resetpasswordform').submit(function(event) {
    event.preventDefault();
    let $form = $(this).serialize(), link = $(this).attr('action');
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
      success: function(data) {
        let $res = eval(data);
        updatepage($res.string, 'resetpasswordresult');
      },
    });
  });
});
