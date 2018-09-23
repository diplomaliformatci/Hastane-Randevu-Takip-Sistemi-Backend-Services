<?php

if (empty($_REQUEST["startDate"]) || empty($_REQUEST["endDate"]) || empty($_REQUEST["type"])  ) {
    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Required Information";
    echo json_encode($returnArray);
    return;
}





$startDate = htmlentities($_REQUEST["startDate"]);
$endDate = htmlentities($_REQUEST["endDate"]);
$type = htmlentities($_REQUEST["type"]);

$file = parse_ini_file("../../../hospital.ini");



$host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("secure/access.php");
    $access = new access($host , $user , $pass , $name);
    $access-> connect();


    $result = $access->findCollisionForPaitentDoctor($type , $startDate , $endDate);

    
    echo json_encode($result);



?>