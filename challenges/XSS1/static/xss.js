var originalAlert = window.alert;

window.alert = function(message) {
    var form = $("#xss-form");

    $.ajax({
        type: "POST",
        url: window.location.href,
        data: "success=true",
        success: function(){
            originalAlert(message+"\n\nCongratulations !! You successfully completed the challenge.");
            window.location.href= window.location.href;
        }
    });
}
