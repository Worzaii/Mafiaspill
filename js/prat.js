/*Reimplementing functions in jQuery*/
const wait = 1 * 1000;
const $text = $('#write');
const $chat = $('#praten');
var checking = false;
var getting;

$(document).ready(function () {
    getChat();
});
$text.keypress(function (key) {
    if (key.keyCode === 13) {
        sendChat();
    }
});

const sendChat = () => {
    $.ajax('prat.php', {
        method: 'GET',
        data: {'write': $text.val()},
        before: () => {
            $text.prop('disabled', true);
        },
        success: (data, status, xhr) => {
            $text.prop('disabled', false).val('');
            console.log('Message sent, removed content from field.');
        },
        error: (data, status, chr) => {
            alert('Kunne ikke sende melding, prøv igjen senere!' + data);
        },
    });
};

const getChat = () => {
    $.ajax('prat.php', {
        method: 'GET',
        success: (data, status, xhr) => {
            $chat.html(data);
        },
        timeout: 5000,
        async: true,
    }).then(() => {
        setTimeout(getChat, 3000);
    });
};