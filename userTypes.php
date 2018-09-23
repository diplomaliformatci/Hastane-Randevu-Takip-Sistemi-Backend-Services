<?php

    
    $file = parse_ini_file("../../../hospital.ini");
    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("secure/access.php");
    $access = new access($host , $user , $pass , $name);
    $access-> connect();

    $returnArray = $access->findUserTypes();

    echo json_encode($returnArray);


?>