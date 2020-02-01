function updatepage(str, responsediv) {
  let rese = '#' + responsediv;
  $(rese).
  fadeIn(1000).
  html(str).
  css({'display': 'block', 'color': 'red', 'word-break': 'break-all'});
}

$(document).ready(function() {
  $('#loginform').submit(function(event) {
    event.preventDefault();
    let $form = $(this).serialize(), link = $(this).attr('action');
    console.log($form);
    $.ajax({
      url: link,
      data: $form,
      dataType: 'json',
      type: 'POST',
      success: function(data) {
        const $res = eval(data);
        console.log($res);
        if ($res.state === 1) {
          updatepage($res.string, 'loginresult');
          let count = 1.5;
          setInterval(function() {
            if (count === 0) {
              window.location.href = $res.href;
            }
            count -= 0.5;
          }, 500);
        } else if ($res.state === 0) {
          updatepage($res.string, 'loginresult');
        }
      },
      error: function(data) {
        console.log(data);

        updatepage('Feil skjedde ved databehandling, tilbakemelding:' +
            data.responseText, 'loginresult');
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