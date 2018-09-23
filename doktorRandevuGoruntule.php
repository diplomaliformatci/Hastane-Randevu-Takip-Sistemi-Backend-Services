<?php

if (empty($_REQUEST["doctorId"])) {
    
    
    
    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Required Information";
    echo json_encode($returnArray);
    
}




$doctorId = htmlentities($_REQUEST["doctorId"]);
    
    $file = parse_ini_file("../../../hospital.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);


    require("secure/access.php");

    $access = new access($host , $user , $pass , $name);
    $access->connect();

    $result = $access->doktorRandevuGoruntule($doctorId);

    echo json_encode($result);

?>