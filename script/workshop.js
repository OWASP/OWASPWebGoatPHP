var overviewHash = "overview";

// Event for form Handler
$(document).on('submit', 'form', function(e){
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(data){
            if (data.status === true) {
                alert("Success! " + data.message);
            } else {
                alert("Sorry, there was an error: " + data.message);
            }
        }
    });
});

$(document).ready(function(){
    $(window).bind('hashchange', function() {
        chooseWindow();
    });

    if (!window.location.hash) {
        window.location.hash = overviewHash;
    } else {
        // If hash is already present in the URL
        chooseWindow();
    }

    $('.btn-toggle').click(function() {
        $(this).find('.btn').toggleClass('active');
        if ($(this).find('.btn-primary').size()>0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }

        $(this).find('.btn').toggleClass('btn-default');

        var parameter;
        if ($(this).find('.btn.active').html() == "ON") {
            parameter = "show";
        } else {
            parameter = "hide";
        }

        var lessonName = $(this).parent().siblings().eq(1).html();
        $.ajax({
            type: "POST",
            url: window.location.pathname,  // Post to current URL
            data: parameter+"="+lessonName
        });

    });
});

function chooseWindow()
{
    var hashInUrl = window.location.hash.substr(1);
    $("#side-nav").find("li.active").removeClass("active");

    switch (hashInUrl) {
        case "overview":
            showOverview();
            break;

        case "reports":
            showReports();
            break;

        case "analytics":
            showAnalytics();
            break;

        case "create":
            showCreateUser();
            break;

        case "delete":
            showDeleteUser();
            break;

        case "settings":
            showSettings();
            break;

        default :
            window.location.hash = overviewHash;
            showOverview();
            break;
    }
}

function showOverview()
{
    $("#heading").html("Overview");
    $("#overview").addClass("active");
    $("#main-content").html($("#overview-content").html());
}

function showReports()
{
    $("#heading").html("Reports");
    $("#reports").addClass("active");
    $("#main-content").html($("#reports-content").html());
}

function showAnalytics()
{
    $("#heading").html("Analytics");
    $("#analytics").addClass("active");
    $("#main-content").html($("#analytics-content").html());
}

function showCreateUser()
{
    $("#heading").html("Create Users");
    $("#create").addClass("active");
    $("#main-content").html($("#create-user-content").html());
    $("#create-user-form-hidden")
}

function showDeleteUser()
{
    $("#heading").html("Delete Users");
    $("#delete").addClass("active");

    $.ajax({
        url: "user/get",
        dataType: "json",
        success: function(data) {
            var table = $("#delete-user-list");
            table.html('');

            $.each(data, function (i, object) {
                table.append("<tr><td>"+object.Username+"</td></tr>");
            });
        }
    });

    $("#main-content").html($("#delete-user-content").html());

}

function showSettings()
{
    $("#heading").html("Lesson Settings");
    $("#settings").addClass("active");
    $("#main-content").html($("#lesson-settings-content").html());
}
