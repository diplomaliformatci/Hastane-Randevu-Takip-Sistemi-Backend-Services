<?php

    if(empty($_REQUEST["username"]) || empty($_REQUEST["password"])
      || empty($_REQUEST["email"]) || empty($_REQUEST["fullname"]) 
      || empty($_REQUEST["type"])) {
        $returnArray["status"] = "400";
        $returnArray["message"] = "Missing Requored Information";
        echo json_encode($returnArray);
        
    }


    $username = htmlentities($_REQUEST["username"]);
    $password = htmlentities($_REQUEST["password"]);
    $email = htmlentities($_REQUEST["email"]);
    $fullname = htmlentities($_REQUEST["fullname"]);
    $type = htmlentities($_REQUEST["type"]);



    $fileName = "../../../hospital.ini";
    
    if(!empty($fileName)) {
        $file = parse_ini_file($fileName);
        
    }
    
    $salt = openssl_random_pseudo_bytes(20);
    $secured_password = sha1($password . $salt);


    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("secure/access.php");
    
    $access = new access($host , $user , $pass , $name);
    $access->connect();
    
    $result = $access->registerUser($username , $secured_password , $salt , $email ,$type ,  $fullname);

    if($result) {
        $user = $access->selectUser($username , $type);
        
        $returnArray["status"] = "200";
        $returnArray["message"] = "Succesfully registered";
        $returnArray["id"] = $user["id"];
        $returnArray["username"] = $user["username"];
        $returnArray["email"] = $user["email"];
        $returnArray["fullname"] = $user["fullname"];
        $returnArray["type"] = $user["type"];
        
        
        
        
    }

$access->disconnect();

echo json_encode($returnArray);



?>