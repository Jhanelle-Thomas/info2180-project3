<?php

$host = getenv('IP');
$username = getenv('C9_USER');
$password = '';
$dbname = 'schema_db';

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo $e;
}

function homepage2($conn) {
    echo('<!DOCTYPE html>
        <html>
        <head>
        </head>
        <body>
        <h1>Homepage</h1>
        <div id = "controls">
        <button id = "newMessage">Send New Message</button>
        <button id = "logout">Logout</button>
        <button id = "addUser">Add User</button>
        </div>
        <div id ="messages">
        <ul>');
                 
    $i = $_SESSION["id"];
    $log = "SELECT * FROM Message WHERE recipient_ids ='$i';";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    
    if (sizeof($result) > 10) {
        for ($x = 0; $x <= 10; $x++) {
            if (!findread($conn, $result[$x]["id"])) {
                echo('<a href = "" ><li class = "mess"><b><h3>' . $result[$x]["subject"] . '</h3></b>
                <b><p>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</p></b>
                <b><p>Date: '. $result[$x]["date_sent"] .'</p></b></li></a>');
            } else {
                echo('<li class = "mess"><h3>' . $result[$x]["subject"] . '</h3><p>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</p>
                <p>Date: '. $result[$x]["date_sent"] .'</p></li>');
            }
        }
    } else {
        foreach($result as $m) {
            if (!findread($conn, $result[$x]["id"])) {
                echo('<li class = "mess"><b><h3>' . $result[$x]["subject"] . '</h3></b>
                <b><p>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</p></b>
                <b><p>Date: '. $result[$x]["date_sent"] .'</p></b></li>');    
            } else {
                echo('<li class = "mess"><h3>' . $m["subject"] . '</h3><p>Sender: ' . findusername($conn, $m["user_id"]) .'</p>
                <p>Date: '. $m["date_sent"] .'</p></li>');
            }
        }
    }
    echo('</ul></div><script id = "js" type="text/javascript" src="homepage.js"></script></body></html>');
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    //User Login
    $uname = $_POST["username"];
    $pass = $_POST["password"];
    
    //checks if email and password are set
    if(isset($uname) && isset($pass)){
        
        //gets user with that email from the database
        $log = "SELECT * FROM User WHERE username ='$uname';";
        $q = $conn->query($log);
        $result = $q->fetchAll(PDO::FETCH_ASSOC);
        
        //checks that user exists and password matches
        if(count($result) > 0 && md5($pass) == $result[0]["password"]){
            $_SESSION["id"] = $result[0]["id"];
            $_SESSION["firstname"] = $result[0]["firstname"];
            $_SESSION["lastname"] = $result[0]["lastname"];
            $_SESSION["username"] = $result[0]["username"];
                
            homepage($conn);
        } else {
            header('Location: /index.html');
        }
    }
}
?>