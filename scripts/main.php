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

session_start();

function isuser($conn, $uid){
    $log = "SELECT * FROM User;";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($result as $r){
        if ($uid == $r["id"]){
            return true;
        }
    }
    return false;
}

function findusername($conn, $id){
    $log = "SELECT * FROM User WHERE id ='$id';";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]["username"];
}

function finduserid($conn, $name){
    $log = "SELECT * FROM User WHERE username ='$name';";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]["id"];
}

//returns true if message is read
function findread($conn, $m_id){
    $log = "SELECT * FROM Message_read WHERE message_id ='$m_id';";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    if (sizeof($result) > 0) {
        return true;
    }
    return false;
}

function homepage($conn) {
    echo('<!DOCTYPE html>
        <html>
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
    $c = 0;
    
    if (sizeof($result) > 10) {
        for ($x = 0; $x <= 10; $x++) {
            $c += 1;
            $id = "mess".$c;
            
            if (!findread($conn, $result[$x]["id"])) {
                echo('<li><h3 id = '.$id.'><b>' . $result[$x]["subject"] . '</b></h3>
                <p><b>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</b></p>
                <p><b>Date: '. $result[$x]["date_sent"] .'</b></p></li>');
            } else {
                echo('<li><h3 id = '.$id.'>' . $result[$x]["subject"] . '</h3><p>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</p>
                <p>Date: '. $result[$x]["date_sent"] .'</p></li>');
            }
            echo('<script type = "text/javascript">
                var m = $("#'.$id.'");
                $(m).click(function() {
                    $.ajax({
                        method: "POST",
                        url: "scripts/main.php",
                        data: {mesdetsub: "'.$result[$x]["subject"].'", mesdettim: "'.$result[$x]["date_sent"].'" }
                    }).done(function(response) {
                        $(result).html(response);
                    }).fail(function() {
                        $(result).html("There was a problem with the request.");
                    });
                });</script>');
        }
    } else {
        foreach($result as $m) {
            $c += 1;
            $id = "mess".$c;
            if (!findread($conn, $m["id"])) {
                echo('<li><h3 id = '.$id.'><b>' . $m["subject"] . '</b></h3>
                <p><b>Sender: ' . findusername($conn, $m["user_id"]) .'</b></p>
                <p><b>Date: '. $m["date_sent"] .'</b></p></li>');    
            } else {
                echo('<li><h3 id = '.$id.'>' . $m["subject"] . '</h3><p>Sender: ' . findusername($conn, $m["user_id"]) .'</p>
                <p>Date: '. $m["date_sent"] .'</p></li>');
            }
            echo('<script type = "text/javascript">
                var m = $("#'.$id.'");
                $(m).click(function() {
                    $.ajax({
                        method: "POST",
                        url: "scripts/main.php",
                        data: {mesdetsub:"'.$m["subject"].'", mesdettim: "'.$m["date_sent"].'" }
                    }).done(function(response) {
                        $(result).html(response);
                    }).fail(function() {
                        $(result).html("There was a problem with the request.");
                    });
                });</script>');
        }
    }
    echo('</ul></div><script id = "js" type="text/javascript" src="js/homepage.js"></script></body></html>');
}

function allmessages($conn){
    echo '<!DOCTYPE html>
        <html>
        <head>
        </head>
        <body>
        <h1>Messages</h1>
        <div id = "controls">
        <button id = "newMessage">Write New Message</button>
        <button id = "logout">Logout</button>
        <button id = "addUser">Add User</button>
        </div>
        <div id ="messages">
        <ul>';
                 
    $i = $_SESSION["id"];
    $log = "SELECT * FROM Message WHERE recipient_ids ='$i';";
    $q = $conn->query($log);
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($result as $m) {
        if (!findread($conn, $result[$x]["id"])) {
            echo('<li class = "mess"><h3><b>' . $result[$x]["subject"] . '</b></h3>
            <p><b>Sender: ' . findusername($conn, $result[$x]["user_id"]) .'</b></p>
            <p><b>Date: '. $result[$x]["date_sent"] .'</b></p></li>');  
        } else {
            echo '<li class = "mess"><h3>' . $m["subject"] . '</h3><p>Sender: ' . findusername($conn, $m["user_id"]) .'</p>
            <p>Date: '. $m["date_sent"] .'</p></li>';
        }
    }
    echo '</ul></div><script id = "js" type="text/javascript" src="homepage.js"></script></body></html>';
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    
    //Create New User
    //checks if firstname and lastname are set    
    if(isset($_POST["firstname"])&&!empty(trim($_POST["firstname"]))&&isset($_POST["lastname"])&&!empty(trim($_POST["lastname"]))) {
        //checks if username is set
        if(isset($_POST["username"])&&!empty(trim($_POST["username"]))){
            //checks if password is set
            if(isset($_POST["password"])&&!empty(trim($_POST["password"]))) {
                $reg ='/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)[A-Za-z\\d$@$!%*#?&.,]{8,}$/';
                //checks if password matches regex
                if (preg_match($reg, $_POST["password"])) {
                    $fname = strip_tags(trim($_POST["firstname"]));
                    $lname = strip_tags(trim($_POST["lastname"]));
                    $uname = strip_tags(trim($_POST["username"]));
                    $pass = password_hash(strip_tags(trim($_POST["password"])), PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO User(firstname,lastname,username,password) VALUES('$fname','$lname','$uname','$pass');";
                    $conn->exec($sql);
                    homepage($conn);
                }
            }
        }
    }
    
    
//#####################################################################################################################################
    
    
    //User Login
    $uname = strip_tags(trim($_POST["uname"]));
    $pass = strip_tags(trim($_POST["pass"]));
    
    //checks if email and password are set
    if(isset($uname) && isset($pass)){
        //gets user with that email from the database
        $log = "SELECT * FROM User WHERE username ='$uname';";
        $q = $conn->query($log);
        $result = $q->fetchAll(PDO::FETCH_ASSOC);
        
        //checks that user exists and password matches
        //if(count($result) > 0 && md5($pass) == $result[0]["password"]){
        if(count($result) > 0 && password_verify ($pass , $result[0]["password"])) {
        //if(count($result) > 0 && $pass == $result[0]["password"]){
            $_SESSION["id"] = $result[0]["id"];
            $_SESSION["firstname"] = $result[0]["firstname"];
            $_SESSION["lastname"] = $result[0]["lastname"];
            $_SESSION["username"] = $result[0]["username"];
            homepage($conn);
        }
    }
    

//#####################################################################################################################################
    
    
    //User Logout
    $logout = $_POST["logout"];
    if(isset($logout) && $logout) {
        session_unset();
        session_destroy();
        header('Location: /index.html');
    }
    
    
//#####################################################################################################################################


    //Send Message
    if(isset($_POST["recipients"])&&!empty(trim($_POST["recipients"]))&&isset($_POST["subject"])&&!empty(trim($_POST["subject"]))) {
        if(isset($_POST["body"])&&!empty(trim($_POST["body"]))){
            
            $recp = explode(";",trim($_POST["recipients"]));
            $subject = strip_tags(trim($_POST["subject"]));
            $body = strip_tags(trim($_POST["body"]));
            $userid = finduserid($conn, $_SESSION["username"]);
            $date = date("y-m-d", time());
            
            foreach ($recp as $rc) {
                if (isuser($conn,finduserid($conn, strip_tags(trim($rc))))) {
                    $r = finduserid($conn, strip_tags(trim($rc)));
                    $sql = "INSERT INTO Message(recipient_ids,user_id,subject,message_body,date_sent) VALUES('$r','$userid','$subject','$body','$date');";
                    $conn->exec($sql);
                }
            }
            homepage($conn);
        } 
    }
    
    
//#####################################################################################################################################


    //Open Message
    if(isset($_POST["mesdetsub"])&&!empty(trim($_POST["mesdetsub"]))&&isset($_POST["mesdettim"])&&!empty(trim($_POST["mesdettim"]))) {
        echo('<!DOCTYPE html>
        <html>
        <head>
        </head>
        <body>
        <h1>Message</h1>
        <div id = "controls">
        <button id = "homepage">Homepage</button>
        <button id = "newMessage">Send New Message</button>
        <button id = "logout">Logout</button>
        <button id = "addUser">Add User</button>
        </div>
        <div>');
        
        $i = $_SESSION["id"];
        $s = $_POST["mesdetsub"];
        $d = $_POST["mesdettim"];
        $date = date("y-m-d", time());
        
        $log = "SELECT * FROM Message WHERE recipient_ids ='$i' AND subject='$s' AND date_sent='$d';";
        $q = $conn->query($log);
        $result = $q->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($result as $m){
            echo('<li><h2>' . $m["subject"] . '</h2><p>Sender: ' . findusername($conn, $m["user_id"]) .'</p>
            <p>Message: '. $m["message_body"].'</p><p>Date: '. $m["date_sent"] .'</p></li>');
            
            $mid = $m["id"];
            $sql = "INSERT INTO Message_read(message_id,reader_id,date_read) VALUES('$mid','$i','$date');";
            $conn->exec($sql);
        }
        echo('</div><script id = "js" type="text/javascript" src="js/message.js"></script></body></html>');
    }
    

//#####################################################################################################################################


    //View All Messages
    //allmessages($conn);
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    homepage($conn);
}
?>