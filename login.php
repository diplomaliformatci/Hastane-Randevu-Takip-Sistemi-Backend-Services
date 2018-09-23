<?php
if (empty($_REQUEST["username"]) || empty($_REQUEST["password"]) || empty($_REQUEST["type"])  ) {
    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Required Information";
    echo json_encode($returnArray);
    return;
}




    $username = htmlentities($_REQUEST["username"]);
    $password = htmlentities($_REQUEST["password"]);
	$type = htmlentities($_REQUEST["type"]);
    $file = parse_ini_file("../../../hospital.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("secure/access.php");
    $access = new access($host , $user , $pass , $name);
    $access-> connect();

    $user = $access->getUser($username , $type);

    if(empty($user)) {
        $returnArray["status"] = "403";
        $returnArray["message"] = "User is not found";
        echo json_encode($returnArray);
        return;
    }

    $secured_password = $user["password"];
    $salt = $user["salt"];
    
    if ($secured_password = sha1($password ,  $salt)) {
        $returnArray["status"] = "200";
        $returnArray["message"] = "Logged in successfully";
        $returnArray["id"] = $user["id"];
        $returnArray["username"] = $user["username"];
        $returnArray["email"] = $user["email"];
        $returnArray["fullname"] = $user["fullname"];
        $returnArray["userType"] = $user["type"];
     } else {
        $returnArray["status"] = "403";
        $returnArray["message"] = "Passowrds do not match";
        
    }

    $access->disconnect();

    echo json_encode($returnArray);










    
    
    
    ?>