$(document).ready(function() {
    var homepage = $("#homepage");
    var logout = $("#logout");
    var sendmessage = $("#newMessage");
    var createButton = $("#create");
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
            alert("You have been logged out.");
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
    
    sendmessage.on("click", function() {
        $(result).load("createmessage.html");
    });
    
    createButton.on("click", function(element) {
        element.preventDefault();
        $.ajax("scripts/main.php", {
            method: 'POST',
            data: { firstname: $("#fname").val(), lastname: $("#lname").val(), username: $("#uname").val(), password: $("#password").val() }
        }).done(function(response) {
            $(result).html(response);
        }).fail(function() {
            $(result).html("There was a problem with the request.");
        });
    });
});