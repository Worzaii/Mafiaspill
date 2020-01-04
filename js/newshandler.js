/* TODO:
*   Replace with a function to send AJAX and then update the webpage
    depending on the content returned
*/
function choice(id, action) {
  if (action === 1 || action === 2 || action === 3) {
    $.ajax('handlers/newshandler.php', {
      data: 'action=' + action + '&id=' + id,
      dataType: 'json',
      type: 'POST',
      success: function(data) {
        let result;
        console.log(data);
        let row = '#tr' + id;
        if (action == 2) {
          if (data.state == 1) {
            $(row).remove();
            result = '<p class=\'lykket\'>' + data.string + '</p>';
          } else {
            result = '<p class=\'warning\'>' + data.string + '</p>';
          }
        } else if(action == 3) {
          if(data.state == 1){
            result = '<p class=\'lykket\'>' + data.string + '</p>';
          } else {
            result = '<p class=\'warning\'>' + data.string + '</p>';
          }
        } else {
          result = '<p class=\'info\'>' + data.string + '</p>';
        }
        updatepage(result, 'newsresult');
      },
      error: function(data) {
        console.log(data);
        const result = '<p class=\'feil\'>' + data.string + '</p>';
        updatepage(result, 'newsresult');
      },
    });
  }
  return false;
}

function deletenews(id, action) {
  if (confirm('Er du sikker på at du vil slette nyheten?')) {
    choice(id, action);
  }
  return false;
}

function updatepage(str, responsediv) {
  let rese = '#' + responsediv;
  $(rese).
  fadeIn(1000).
  html(str).
  css({'display': 'block', 'color': 'red', 'word-break': 'break-all'});
}