function updatepage(str, responsediv) {
    let rese = "#" + responsediv;
    $(rese).fadeIn(1000);
    document.getElementById(responsediv).innerHTML = str;
    document.getElementById(responsediv).style.display = "block";
    document.getElementById(responsediv).style.color = "red";
}

$(document).ready(function () {
    $("#loginform").submit(function (event) {
        event.preventDefault();
        let $form = $(this).serialize(), link = $(this).attr('action');
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST",
            success: function (data) {
                let $res = eval(data);
                console.log($res);
                if ($res.state === 1) {
                    updatepage($res.string, "loginresult");
                    let count = 1.5;
                    setInterval(function () {
                        if (count === 0) {
                            window.location.href = href;
                        }
                        count -= 0.5;
                    }, 500);
                } else if ($res.state === 0) {
                    updatepage($res.string, "loginresult");
                }
            }
        });
    });
    $("#forgottenpassword").submit(function (event) {
        event.preventDefault();
        let $form = $(this).serialize(), link = $(this).attr('action');
        link += "?forgotpassword";
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST"
        }).done(function (data) {
            let $res = eval(data);
            updatepage($res.string, "res3");
        });
    });
    $("#getaccess").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        link += "?getaccess";
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST"
        }).done(function (data) {
            let $res = eval(data);
            updatepage($res.string, "ressu");
        });
    });
    $("#register").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST"
        }).done(function (data) {
            let $res = eval(data);
            updatepage($res.string, "ressu");
        });
    });
    $("#resetpassword").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST",
            success: function (data) {
                let $res = eval(data);
                updatepage($res.string, "ressu");
            }
        });
    });
});