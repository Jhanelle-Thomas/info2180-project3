$(document).ready(function() {
    var addUser = $("#addUser");
    var logout = $("#logout");
    var sendmessage = $("#newMessage");
    var result = $("#result");
    var messages = document.getElementsByClassName("mess");
    
    for (var x = 0; x < messages.length; x++) {
        messages[x].onclick = function() {
            console.log(messages[x]);
            /*$.ajax({
            method: 'POST',
            url: "main.php",
            data: {mesdetsub: this }
        }).done(function(response) {
            $(result).html(response);
            alert("You have been logged out.");
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });*/
        };
    }
    
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