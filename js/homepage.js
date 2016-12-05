$(document).ready(function() {
    var addUser = $("#addUser");
    var logout = $("#logout");
    var sendmessage = $("#newMessage");
    var result = $("#result");
    
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