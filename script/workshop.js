var overviewHash = "overview";

$(document).ready(function(){
    $(window).bind('hashchange', function(e) {
        chooseWindow();
    });

    if (!window.location.hash) {
        window.location.hash = overviewHash;
    }
});

function chooseWindow()
{
    var hashInUrl = window.location.hash.substr(1);

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
    alert("Overview");
}

function showReports()
{
    alert("Reports");
}

function showAnalytics()
{
    alert("Analytics");
}

function showCreateUser()
{
    alert("Create Users");
}

function showDeleteUser()
{
    alert("Delete Users");
}

function showSettings()
{
    alert("Settings");
}
