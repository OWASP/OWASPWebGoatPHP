$(document).ready(function(){

    $("#lesson-plan-btn").click(function(){
        $.ajax({
            url: 'static/plan.html',
            success: function(data) {
                $(".ajax-content").html(data);
            }
        });

        $("#pop-up-div").reveal();
    });

    $("#solution-btn").click(function(){
        $.ajax({
            url: 'static/solution.html',
            success: function(data) {
                $(".ajax-content").html(data);
            }
        });

        $("#pop-up-div").reveal();
    });

    $("#parameter-btn").click(function(){
        var $_GET = {};
        var flag = 0;

        $("#options-container").html("");
        document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
            function decode(s) {
                return decodeURIComponent(s.split("+").join(" "));
            }
            $("#options-container").append("Parameter: "+decode(arguments[1])+", Value: "+decode(arguments[2])+"<br>");
            flag = 1;
        });

        if (!flag) {
            $("#options-container").html("No GET parameters found !");
        }
    });

    $("#cookie-btn").click(function(){
        $("#options-container").html(document.cookie);
    });

    $("#hints-btn").click(function(){
        //Returns a random hint
        //alert(hints[Math.floor((Math.random() * (hints.length - 1)))]);
        $("#options-container").html(hints[Math.floor((Math.random() * (hints.length - 1)))]);
    });

});