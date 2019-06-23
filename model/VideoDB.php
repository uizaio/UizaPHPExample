<?php

namespace model;

require_once __DIR__ . '/../model/Database.php';

/**
 * Description of VideoDB
 *
 * @author ducla
 */
class VideoDB extends \Database{
    
    public function insert($entityId, $videoName){
        $query = $this->connection->prepare("INSERT INTO "
                .$this->VIDEO_DB
                ."(id, entityId, videoName, createdAt, updatedAt "
                . ") "
                . "VALUES(NULL,?, ? ,?,?"
                . ")");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $updated_date = $this->getCreatedDate();
        $created_date = $updated_date;
        $query->bind_param("ssss", $entityId, $videoName, $created_date, $updated_date);
        $result = $query->execute();
        if(!$result){
            errorLogger(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function listVideo($index, $size){
        $query = $this->connection->prepare("SELECT "
                . "id,entityId,videoName"
                . " FROM "
                . $this->VIDEO_DB
                . " limit ?,?");
        if (!$query) {
            errorLogger("videodb:" . mysqli_error($this->connection), "error");
        }
        $query->bind_param("ii", $index, $size);
        $query->execute();
        $videos = $this->generateResult($query);
        return $videos;
    }
    
    public function totalVideo(){
        $squery = "SELECT "
                . "count(id)"
                . " FROM "
                .$this->VIDEO_DB;
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger("videodb:".mysqli_error($this->connection), "error");
        }
        $query->execute();
        $query->bind_result($total);
        while($query->fetch()){
            return $total;
        }
    }
    
    public function isVideoExist($entityId){
        $query = $this->connection->prepare("SELECT "
                . "entityId"
                . " FROM "
                . $this->VIDEO_DB
                . " WHERE entityId = ?");
        if (!$query) {
            errorLogger("videoDB:" . mysqli_error($this->connection), "error");
        }
        $query->bind_param("s", $entityId);
        $query->execute();
        $query->store_result();
        if($query->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function getVideo($id){
        $query = $this->connection->prepare("SELECT "
                . " * "
                . " FROM "
                . $this->VIDEO_DB
                . " WHERE id = ?");
        if (!$query) {
            errorLogger("videodb:" . mysqli_error($this->connection), "error");
        }
        $query->bind_param("i", $id);
        $query->execute();
        $videos = $this->generateResult($query);
        return $videos;
    }
}
