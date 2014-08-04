/*********************************
 * Script for contest home       *
 *********************************/

$(document).ready(function(){
    var countdownTimer = $('.countdown-clock').FlipClock(5, {
        countdown: true,
        callbacks: {
            stop: function() {
                // Refresh the page
                window.location.href = window.location.href;
            }
        }
    });
});
