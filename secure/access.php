<?php

    class access {
        var $host = null;
        var $user = null;
        var $password = null;
        var $name = null;
        var $conn = null;
        var $result = null;
        
        
        
        
    


    function __construct($dbhost , $dbuser , $dbpass , $dbname) {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->password = $dbpass;
        $this->name = $dbname;
    }



     public function connect() {
        
        $this->conn = new mysqli($this->host , 
                                 $this->user , 
                                 $this -> password , 
                                 $this->name);
        
        if (mysqli_connect_errno()) {
            echo "Could Not Connect to Database";
        }
        
        
        $this->conn->set_charset("utf-8");
        
        
        
        
        
    }



     function disconnect() {
        if ($this->conn != null) {
            $this->conn->close();
        }
    }


     function getUser($username , $type) {
        $returnArray = array();
        
        if ($type == "Hasta"){
        	$sql = "SELECT * FROM patient WHERE username='".$username."'";
        }
        
        
        
        else  {
        	$sql = "SELECT * FROM doctor WHERE username='".$username."'";
        }
        
        
        $result = $this->conn->query($sql);
        
        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            
            if (!empty($row)) {
                $returnArray = $row;
            }
            
        }
        
        
        return $returnArray;
        
    }




     function registerUser($username , $password , $salt , $email , $type , $fullname) {
         
         
         
        if ($type == "Hasta") {
            $sql = "INSERT INTO patient SET username=? , password=? , salt=? , email=? ,type=?,fullname=?";
            
            $statement = $this->conn->prepare($sql);
            
            
            
            
            if(!$statement){
                throw new Exception($statement->error);
            }
            
            
            
            $statement->bind_param("ssssss" , $username , $password , 
                                  $salt , $email , $type , $fullname);
            
            
            $returnValue = $statement->execute();
            
            return $returnValue;
            
            
            
            
        } else {
            
            
            //sql command
            $sql = "INSERT INTO doctor SET username=?,password=?,  salt=? , email=? , type=? , fullname=?";
            //store query in statement
            $statement = $this->conn->prepare($sql);
            
            // If error
            if (!$statement) {
                throw new Exception($statement->error);
            }
            
            // bind 5 param of type string to be placed in sql command
            $statement->bind_param("ssssss" , $username , $password , $salt , $email ,$type ,  $fullname);
            
            $returnValue = $statement->execute();
            
            return $returnValue;
            
            
            
            
        }
        
        
        
    }


        
        
        public function selectUser($username , $type) {
            if ($type == "Hasta") {
                $sql = "SELECT * FROM patient WHERE username='".$username."'";
        
        //assign result we got from $sql to $result var
        $result = $this->conn->query($sql);
        
        // if we have at least 1 result returned
        if($result != null && (mysqli_num_rows($result) >= 1 )) {
            //assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            if (!empty($row)) {
                $returnArray = $row;
                
            }
            
        }
        
        return $returnArray;
        
            } else {
                
                
                
                $sql = "SELECT * FROM doctor WHERE username='".$username."'";
        
        //assign result we got from $sql to $result var
        $result = $this->conn->query($sql);
        
        // if we have at least 1 result returned
        if($result != null && (mysqli_num_rows($result) >= 1 )) {
            //assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            if (!empty($row)) {
                $returnArray = $row;
                
            }
            
        }
        
        return $returnArray;
        
                
                
                
                
                
                
                
            }
        }
        
        
        
        
     function insertRandevuTime($userId , $startDate , $doctorType) {
         $sql = "Select * FROM randevu where doctorId='".$userId."' AND 
          randevuDate='".$startDate."' ";
         
        
         $result = $this->conn->query($sql);
         
         $returnArray = array();
         $row = $result->fetch_array(MYSQLI_ASSOC);
         
         
         if ((mysqli_num_rows($result) <= 0)) {
             $sql = "INSERT INTO randevu (doctorId , randevuDate , doctorType) 
             VALUES('".$userId."' , '".$startDate."' , '"
                 .$doctorType."'   )    ;";
            $result = $this->conn->query($sql);
            $row = $result->fetch_array(MYSQLI_ASSOC);
             
             if (!empty($row)) {
                 $returnArray["doctorId"] = $userId;
                 $returnArray["Success"] = "Successfully added new Item";
             } else {
                 $returnArray["Success"] = "Failed to insert item";
             }
             
             
             
         }
         
         
         return $returnArray;
         
         
     }
     
        
        
        function findCollisionForPaitentDoctor($type , $startDate , $endDate) {
            $sql = "SELECT * from randevu WHERE doctorType=? AND randevuDate BETWEEN ? AND ? AND patientId is null";
            
            //store query in statement
            $statement = $this->conn->prepare($sql);
            
            // If error
            if (!$statement) {
                throw new Exception($statement->error);
            }
            
            // bind 5 param of type string to be placed in sql command
            $statement->bind_param("sss" , $type , $startDate , $endDate);
            
            $returnValue = $statement->execute();
            
            
            
            
            
            
            
            
            
            $returnArray = array();
            
            $result = $statement->get_result();
            
            
            
            while ($row = $result->fetch_assoc()) {
                $returnArray[] = $row;
             }
            
            return $returnArray;
            
            
            
        }
        
        
        
        
        
        function findUserTypes () {
            $sql = "SELECT DISTINCT doctorType From randevu";
            
            $statement = $this->conn->prepare($sql);
            
            if (!$statement) {
                throw new Exception($statement->error);
                            }
            
            
            $returnValue = $statement->execute();
            
            $result = $statement->get_result();
            
            
            $returnArray = array();
            
            while($row = $result->fetch_assoc()) {
                $returnArray[] = $row;
             }
            
            
            return $returnArray;
            
            
            
            
            
            
            
        }
     
        
        
     function addPatientToRandevu($randevuId , $patientId)  {
        $sql = "UPDATE randevu SET patientId=? WHERE randevuId=?";
         
         $statement = $this->conn->prepare($sql);
        
          if (!$statement) {
                throw new Exception($statement->error);
            }
            
            // bind 5 param of type string to be placed in sql command
            $statement->bind_param("ss" , $patientId , $randevuId);
            
            $returnValue = $statement->execute();
           
         
         
         
     }
        
        
    function getRandevuForPatient($patientId) {
        $sql = "SELECT * FROM randevu WHERE patientId=?";
        
        $statement = $this->conn->prepare($sql);
        
        if (!$statement) {
            throw new Exception($statement->error);
            
        }
        
        
        $statement->bind_param("s" , $patientId);
        
        
        $returnValue = $statement->execute();
            
            $result = $statement->get_result();
            
            
            $returnArray = array();
            
            while($row = $result->fetch_assoc()) {
                $returnArray[] = $row;
             }
            
            
            return $returnArray;
            
        
        
        
        
        
    }
        
        
    function doktorRandevuGoruntule($doctorId) {
        $sql = "SELECT * FROM randevu WHERE doctorId=? AND patientId is not null";
        
        $statement =  $this->conn->prepare($sql);
        
        if(!$statement) {
            throw new Exception($statement->error);
        }
        
        
        $statement->bind_param("s",$doctorId);
        
        $returnValue = $statement->execute();
        
        $result = $statement->get_result();
        
        $returnArray = array();
        
        
        while($row = $result->fetch_assoc()) {
            $returnArray[] = $row;
        }
        
        
        
        
        return $returnArray;
        
        
        
        
        
        
        
    }
        

    }



?>