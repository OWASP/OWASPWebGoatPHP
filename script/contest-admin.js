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

    // To handle the reset password form
    $("#reset-pass-form").submit(function(e){
        e.preventDefault();
        $("#pass-reset-btn").val("Please wait...");

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                $("#pass-reset-btn").val("Submit");

                if (data.status == false) {
                    alert(data.error);
                } else {
                    alert(data.message);
                    $("#old-password").val('');
                    $("#new-password").val('');
                    $("#cnew-password").val('');
                }
            }
        });
    });

    // To handle challenge addition ajax events
    $(".checkbox").click(function(){
        var tr = $(this).parents("tr");

        var challenge = tr.find(".challenge").html();
        var name = tr.find("input[name='name']").val();
        var points = tr.find("input[name='points']").val();
        var flag = tr.find("input[name='flag']").val();

        var currentSNo = -1;   // To store Last S.No of Present Challenges table
        var pChallengesTable = $("#present-challenges-table");

        if (challenge === '') {
            alert("Invalid request");
            return false;
        } else if(name === '') {
            alert("Please input name of challenge");
            return false;
        } else if(!$.isNumeric(points)) {
            alert("Please input points (int only)");
            return false;
        } else if(flag === '') {
            alert("Please input Flag");
            return false;
        }

        tr.addClass("success");

        $.ajax({
            method: "POST",
            url: addChallengeURL,
            data: "challenge="+challenge+"&name="+name+"&points="+points+"&flag="+flag,
            success: function() {
                // When challenge is successfully added, remove it from
                // the "New Challenges" table and insert it in the
                // "Present Challenge" Table
                tr.fadeOut(200);

                if (pChallengesTable.find("tr").length == 0) {
                    // i.e no entry in this table
                    currentSNo = 1;
                    $("#no-challenges").html(""); // Remove the No challenges text
                } else if (currentSNo != -1) {
                    // i.e currentSNO is found in prev request
                    currentSNo++;
                } else {
                    currentSNo = pChallengesTable.children("tr:last").children("td:first").html();
                    currentSNo++;
                }

                pChallengesTable.append(
                    "<tr><td>"+currentSNo+"</td><td>"+challenge+"</td><td>"+
                        name+"</td><td>"+points+"</td></tr>"
                );
            }
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

        case "users":
            showUsers();
            break;

        case "challenges":
            showChallenges();
            break;

        case "settings":
            showSettings();
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
 * To display challenge page
 */
function showChallenges()
{
    $("#heading").html("Challenges");
    $("#challenges").addClass("active");
    $("#challenges-content").removeClass("hidden");
}

/**
 * To display account settings
 */
function showSettings()
{
    $("#heading").html("Account Settings");
    $("#account-settings").addClass("active");
    $("#account-settings-content").removeClass("hidden");
}
