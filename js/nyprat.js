$(document).ready(function () {
    var up = 3;
    uptime = up * 1000;
    $res = $("#praten");
    $res.html("<p style='text-align:center;font-weight:bold;font-size:20px;'>Laster...</p>");
    setInterval(function () {
        $.ajax({
            url: "prat.php",
            dataType: "html"
        }).done(function (data) {
            $res.html(data);
        });
    }, uptime);
    $("#write").on("keyup", function (event) {
        var txtt = $(this).serialize();
        if (event.which === 13) {
            $("#write").attr('disabled', 'disabled');
            $.ajax({
                url: "prat.php",
                data: txtt
            }).success(function (data) {
                var res = eval(data);
                if (res.s === 1) {
                    $("#write").val("");
                } else {
                    alert("Kunne ikke legge inn innlegg!");
                }
                $("#write").removeAttr('disabled');
            });
        }
    });
});