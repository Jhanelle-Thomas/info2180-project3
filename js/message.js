$(document).ready(function() {
    var homepage = $("#homepage");
    var addUser = $("#addUser");
    var logout = $("#logout");
    var sendmessage = $("#newMessage");
    var result = $("#result");
    
    homepage.on("click", function() {
        $.ajax('scripts/main.php', {
            method: 'GET'
        }).done(function(response) {
            $(result).html(response);
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
    
    addUser.on("click", function() {
        $(result).load("adduser.html");
    });
    
    logout.on("click", function() {
        $.ajax({
            method: 'POST',
            url: "scripts/main.php",
            data: { logout: true }
        }).done(function(response) {
            $(result).html(response);
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
    
    sendmessage.on("click", function() {
        $(result).load("createmessage.html");
    });
});