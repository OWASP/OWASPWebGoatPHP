function imageSelect(num)
{
    var img = "<img src='static/image" + num + ".jpg' class='img-responsive'/>";
    $("#img-content").html(img);
    window.location.hash = num;
}

$(document).ready(function(){
    imageSelect(self.location.hash.substr(1) || "1");
});