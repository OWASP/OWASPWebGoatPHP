var cookieName = "unique2u";

function readCookie(name)
{
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');

    for(var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

$(document).ready(function(){
    $("#read-cookie").click(function(e){
        e.preventDefault();
        var cookieValue = readCookie(cookieName);
        alert(cookieValue);

        if (cookieValue == null) {
            if ($("#httpOnly-true").is(":checked")) {
                $.ajax({
                    type: "POST",
                    url: window.location.href,
                    data: "success=true",
                    success: function(){
                        alert("Your browser enforced the HTTPOnly flag properly for the 'unique2u' cookie." +
                            "\n\nLesson Completed");
                        window.location.reload();
                    }
                });
            }
        }
    });

    $("#write-cookie").click(function(e){
        e.preventDefault();
        document.cookie = cookieName + "=hacked;";
    });
});
