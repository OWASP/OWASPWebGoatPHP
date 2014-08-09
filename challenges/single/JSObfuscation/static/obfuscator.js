/**
 * Script to obfuscate the javascript code
 */
var string = '<button onclick=\'javascript:if (document.getElementById("password").value=="itWasE@sz"){alert("Congratulations!");window.location += "?password="+document.getElementById("password").value}else {alert("Invalid Password! Try Again")}\' class="btn btn-default">Confirm</button>';
var result = "(";

for (var i=0; i < string.length; i++) {
    result += "0x";
    result += string.charCodeAt(i).toString(16);
    if (i != (string.length - 1)) {
        result += ",";
    }
}
result += ")";

console.log(result);
