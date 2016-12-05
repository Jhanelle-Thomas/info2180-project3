$(document).ready(function() {
    var loginButton = $("#logButton");
    var result = $("#result");
    var css = $("#css");
    var js = $("#js");
    
    loginButton.on("click", function(element) {
        element.preventDefault();
        
        $.ajax({
            method: 'POST',
            url: "scripts/main.php",
            data: { uname: $("#username").val(), pass: $("#password").val() }
        }).done(function(response) {
            $(result).html(response);
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
});