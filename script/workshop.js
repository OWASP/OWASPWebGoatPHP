/**************************************
 * Script for workshop admin dashboard*
 **************************************/

var defaultHash = "overview";

/**
 * Handles create and delete user forms
 */
$(document).on('submit', 'form', function(e){
    e.preventDefault();
    // Make an AJAX request
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(data){
            if (data.status === true) {
                // On success
                alert("Success! " + data.message);
            } else {
                // On error
                alert("Sorry, there was an error: " + data.message);
            }
        }
    });
});

/**
 * On document ready
 */
$(document).ready(function(){
    // Add a listener if hash is changed
    $(window).bind('hashchange', function() {
        chooseWindow();
    });

    if (!window.location.hash) {
        // If hash is not present in the URL add default hash
        window.location.hash = defaultHash;
    } else {
        // If hash is already present in the URL
        chooseWindow();
    }

    // To handle on/off visibility buttons
    $('.btn-toggle').click(function() {
        $(this).find('.btn').toggleClass('active');
        if ($(this).find('.btn-primary').size()>0) {
            // If btn-primary class is present -> toggle
            $(this).find('.btn').toggleClass('btn-primary');
        }

        $(this).find('.btn').toggleClass('btn-default');

        var parameter;  // To detect if it is show request or hide request
        if ($(this).find('.btn.active').html() == "ON") {
            parameter = "show";
        } else {
            parameter = "hide";
        }

        // Get the name of the lesson
        var lessonName = $(this).parent().siblings().eq(1).html();
        // Make an AJAX request
        $.ajax({
            type: "POST",
            url: window.location.pathname,  // Post to current URL
            data: parameter+"="+lessonName
        });
    });
});

/**
 * Function to display the required window
 */
function chooseWindow()
{
    var hashInUrl = window.location.hash.substr(1);
    $("#side-nav").find("li.active").removeClass("active");
    hidePrevious(); // Hide all previous divs

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
            window.location.hash = defaultHash;
            showOverview();
            break;
    }
}

/**
 * Function to hide all the divs within #main-content
 */
function hidePrevious()
{
    $("#main-content").children().each(function(){
        if (!$(this).hasClass("hidden")) {
            $(this).addClass("hidden");
        }
    });
}

/**
 * To display overview page
 */
function showOverview()
{
    $("#heading").html("Overview");
    $("#overview").addClass("active");
    $("#overview-content").removeClass("hidden");
}

/**
 * To display reports page
 */
function showReports()
{
    $("#heading").html("Reports");
    $("#reports").addClass("active");
    $("#reports-content").removeClass("hidden");
}

/**
 * To display analytics page
 */
function showAnalytics()
{
    $("#heading").html("Analytics");
    $("#analytics").addClass("active");
    $("#analytics-content").removeClass("hidden");
}

/**
 * To display Create User page
 */
function showCreateUser()
{
    $("#heading").html("Create Users");
    $("#create").addClass("active");
    $("#create-user-content").removeClass("hidden");
}

/**
 * To display Delete User page
 */
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

    $("#delete-user-content").removeClass("hidden");
}

/**
 * To display settings page
 */
function showSettings()
{
    $("#heading").html("Lesson Settings");
    $("#settings").addClass("active");
    $("#lesson-settings-content").removeClass("hidden");
}
