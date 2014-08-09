var originalAlert = window.alert;

window.alert = function(message) {
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
