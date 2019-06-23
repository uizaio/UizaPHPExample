<?php

namespace model;
require_once __DIR__ . '/../model/Database.php';

/**
 * Description of UserDB
 *
 * @author ducla
 */
class UserDB extends \Database{
    
    public function create($firstname, $lastname, $email, $password){
        $query = $this->connection->prepare("INSERT INTO "
                .$this->USER_DB
                ."(id, firstname, lastname, email, password,createdAt, updatedAt "
                . ") "
                . "VALUES(NULL,?,?,?,?,?,?"
                . ")");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $updated_date = $this->getCreatedDate();
        $created_date = $updated_date;
        $firstname = htmlspecialchars(strip_tags($firstname));
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));
        $password_hash = password_hash($password, PASSWORD_BCRYPT);//must hash password in database
        $query->bind_param("ssssss", $firstname, $lastname, $email, $password_hash, $created_date, $updated_date);
        $result = $query->execute();
        if(!$result){
            errorLogger(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    function emailExist($email){
        $query = $this->connection->prepare("SELECT id"                
                . " FROM "
                . $this->USER_DB
                . " WHERE email = ?");
        if (!$query) {
            errorLogger("userdb:" . mysqli_error($this->connection), "error");
        }
        $email = htmlspecialchars(strip_tags($email));        
        $query->bind_param("s", $email);
        
        $query->execute();
        $query->store_result();
        if($query->num_rows > 0){            
            return true;
        }else{
            return false;
        }
    }
    
    function getUser($email){
        $query = $this->connection->prepare("SELECT "
                . "id,firstname,lastname,password"
                . " FROM "
                . $this->USER_DB
                . " WHERE email = ?");
        if (!$query) {
            errorLogger("userdb:" . mysqli_error($this->connection), "error");
        }
        $query->bind_param("s", $email);
        $query->execute();
        $user = $this->generateResult($query);
        return $user;
    }
}
