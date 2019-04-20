$(document).ready(function () {
    $(".loginform").find("input").removeAttr("disabled");
    $(".loginform [name='username']").trigger("focus");
    $('#content div').hide();
    $('#content div:first').show();
    $('#content ul li:first').addClass('active');
    $('#content ul a').click(function () {
        $('#content ul li').removeClass('active');
        $(this).parent().addClass('active');
        let currentTab = $(this).attr('href');
        $('#content div').hide();
        $(currentTab).show();
        if (currentTab === "#rules") {
            $("#rules div").css({display: "block"});
        }
        return false;
    });
    $('#kontakt a').click(function () {
        $('#content ul li').removeClass('active');
        $("#content").parent().addClass('active');
        var currentTab = $(this).attr('href');
        $('#content div').hide();
        $(currentTab).show();
        if (currentTab === "#rules") {
            $("#rules div").css({display: "block"});
        }
        return false;
    });
});