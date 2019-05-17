$(document).ready(function () {
    let up = 3;
    let uptime = up * 1000;
    let $res = $("#praten");

    function chatload() {
        $.ajax({
            url: "prat.php",
            dataType: "html"
        }).done(function (data) {
            $res.html(data);
        });
    }

    $res.html("<p style='text-align:center;font-weight:bold;font-size:20px;'>Laster...</p>");
    chatload();
    setInterval(function () {
        chatload();
    }, uptime);
    $("#write").on("keyup", function (event) {
        var txtt = $(this).serialize();
        if (event.which === 13) {
            $("#write").attr('readonly', 'readonly');
            $.ajax({
                url: "prat.php",
                data: txtt,
                success: function (data) {
                    let res = eval(data);
                    if (res.s === 1) {
                        $("#write").val("");
                    } else {
                        alert("Kunne ikke legge inn innlegg!");
                    }
                    $("#write").removeAttr('readonly');
                }
            });
        }
    });
});