$(document).ready(function() {
    var homepage = $("#homepage");
    var logout = $("#logout");
    var addUser = $("#addUser");
    var newMessage = $("#newMessage");
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
    
    addUser.on("click", function() {
        $(result).load("adduser.html");
    });
    
    newMessage.on("click", function(element) {
        element.preventDefault();
        
        $.ajax({
            method: 'POST',
            url: "scripts/main.php",
            data: { recipients: $("#reciever").val(), subject: $("#subject").val(), body: $("#body").val()}
        }).done(function(response) {
            $(result).html(response);
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
});