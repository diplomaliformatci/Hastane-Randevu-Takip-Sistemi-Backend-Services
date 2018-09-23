
<?php

if (empty($_REQUEST["username"]) ||
    empty($_REQUEST["startDate"]) || empty($_REQUEST["type"])) {
    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Required Information";
    echo json_encode($returnArray);
    return;
} 
    

    $username = htmlentities($_REQUEST["username"]);
    $startDate = htmlentities($_REQUEST["startDate"]);
    $type = htmlentities($_REQUEST["type"]);




    $file = parse_ini_file("../../../hospital.ini");
    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);  
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);



    require("secure/access.php");
    $access = new access($host,  $user , $pass , $name);

    $access-> connect();

    $user = $access->getUser($username , $type);
    
    if (empty($user)) {
        $returnArray["status"] = "403";
        $returnArray["message"] = "User is not found";
        echo json_encode($returnArray);
        return;
        
     }


    
    $result =  $access -> insertRandevuTime($user["id"] , $startDate,$type);
    
    echo json_encode($result);
    
    







    
    
    ?>