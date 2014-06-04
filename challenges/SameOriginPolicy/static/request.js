$(document).ready(function(){

    var form = $("#same-origin-frm");

    form.submit(function(e){
        e.preventDefault();

        var requestUrl = $("#url").val();

        $.ajax({
            url: requestUrl,
            success: function() {
                alert("Can execute an AJAX request on this URL");
                $("#hidden-same-origin").val("1");
                makeSuccess(form);
            },
            error: function() {
                alert("Cannot execute AJAX request on this URL");
                $("#hidden-cross-origin").val("1");
                makeSuccess(form);
            }
        });
    });

    $("#same-origin-link").click(function(){
        $("#url").val("static/plan.html");
        $("#same-origin-frm").submit();
    });

    $("#cross-origin-link").click(function(){
        $("#url").val("http://www.google.com/");
        $("#same-origin-frm").submit();
    });

});

function makeSuccess(form)
{
    if ((($("#hidden-same-origin").val() == 1) && ($("#hidden-cross-origin").val() == 1))) {
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function() {
                document.location.reload();
            }
        });
    }
}