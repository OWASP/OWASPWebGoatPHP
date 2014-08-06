/**************************************
 * Script for contest admin dashboard *
 **************************************/

var defaultHash = "overview";

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

        case "users":
            showUsers();
            break;

        case "challenges":
            showChallenges();
            break;

        default:
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
    $("#heading").html("Welcome admin!");
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
 * To display Users page
 */
function showUsers()
{
    $("#heading").html("Users");
    $("#users").addClass("active");
    $("#users-content").removeClass("hidden");
}

/**
 * To display settings page
 */
function showChallenges()
{
    $("#heading").html("Challenges");
    $("#challenges").addClass("active");
    $("#challenges-content").removeClass("hidden");
}
