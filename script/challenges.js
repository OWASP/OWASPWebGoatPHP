$(document).ready(function(){

    $("li.active").closest('div.panel-collapse').addClass('in');

    $("#reset-btn").click(function(e){
        e.preventDefault();

        $(this).html("Resetting....");
        $(this).attr("disabled", "disabled");

        $.ajax({
            url: $(this).attr("href"),
            success: function(data) {
                // This way of refresh will ensure that in firefox
                // POST data is not sent again. Will work even if
                // hash is present in the url.
                // window.location.href = window.location.href; not works
                // if hash is present. window.location.reload(); send
                // the POST data again causing lesson complete again
                window.location = window.location.pathname;
            }
        });
    });

    /**
     * Secure Coding mode functionality
     */
    $("#fix-btn").click(function(){
        $("#source-code").toggleClass("hidden");
    });

    $("#scode-reset-btn").click(function(e){
        e.preventDefault();
        editor.getSession().setValue(initialCode);
    });

    $("#scode-submit-btn").click(function(e){
        e.preventDefault();
        var code = editor.getSession().getValue();
        $("#scode-inp").val(code);
        $("#scode-form").submit();
        console.log(code);
    });

    /********************************************
     *          Code for bottom panel           *
     ********************************************/
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
        if (hints.length == 0) {
            $("#options-container").html("Sorry, no hints present for this lesson");
        }
        $("#options-container").html(hints[Math.round((Math.random() * (hints.length - 1)))]);
    });
});
