<?php

    if (empty($_REQUEST["randevuId"]) || empty($_REQUEST["patientId"])) {
        
        $returnArray["status"] = "400";
        $returnArray["message"] = "Missing Required Information";
        echo json_encode($returnArray);
    }
        
        
        
        
    $randevuId =  htmlentities($_REQUEST["randevuId"]);
    $patientId = htmlentities($_REQUEST["patientId"]);
    $file = parse_ini_file("../../../hospital.ini");
        




        $host = trim($file["dbhost"]);
        $user = trim($file["dbuser"]);
        $pass = trim($file["dbpass"]);
        $name = trim($file["dbname"]);
        
        
        
        require("secure/access.php");
        $access = new access($host , $user , $pass , $name);
        $access->connect();
        $access->addPatientToRandevu($randevuId , $patientId);
        
        
        
        
        
        
        
    
?>