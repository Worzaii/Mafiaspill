function updatepage(str, responsediv) {
    var rese = "#" + responsediv;
    $(rese).fadeIn(1000);
    document.getElementById(responsediv).innerHTML = str;
    document.getElementById(responsediv).style.display = "block";
    document.getElementById(responsediv).style.color = "red";
}
$(document).ready(function () {
    $("#log").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        link += "?log";
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST",
            success: function (data) {
                $res = eval(data);
                console.log($res);
                if ($res.state == 1) {
                    updatepage($res.string, "res1");
                    var count = 1.5;
                    console.log('Counting down from ' + count);
                    countdown = setInterval(function () {
                        console.log('Counting... Val: ' + count);
                        if (count === 0) {
                            window.location.href = $res.href;
                        }
                        count -= 0.5;
                    }, 500);
                } else if ($res.state == 0) {
                    updatepage($res.string, "res1");
                }
                else {
                    alert("Something went wrong");
                }
            }
        });
    });
    $("#gpw").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        link += "?gpw";
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST"
        }).done(function (data) {
            $res = eval(data);
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
            $res = eval(data);
            updatepage($res.string, "ressu");
        });
    });
    $("#brukerreg").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST"
        }).done(function (data) {
            $res = eval(data);
            updatepage($res.string, "ressu");
        });
    });
    $("#respas").submit(function (event) {
        event.preventDefault();
        var $form = $(this).serialize(), link = $(this).attr('action');
        $.ajax({
            url: link,
            data: $form,
            dataType: "json",
            type: "POST",
            success: function (data) {
                $res = eval(data);
                updatepage($res.string, "ressu");
            }
        });
    });
});