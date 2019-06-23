<?php


namespace model;
require_once __DIR__ . '/../model/Database.php';

/**
 * Description of UserVideoDB
 *
 * @author ducla
 */
class UserVideoDB extends \Database{
    
    public function insert($videoId, $userId, $jwt, $expire){
        $query = $this->connection->prepare("INSERT INTO "
                .$this->VIDEO_USER_DB
                ."(id, videoId, userId, token, expire "
                . ") "
                . "VALUES(NULL,?, ?,?,?"
                . ")");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $updated_date = $this->getCreatedDate();
        $created_date = $updated_date;
        $query->bind_param("iisi", $videoId, $userId, $jwt, $expire);
        $result = $query->execute();
        if(!$result){
            errorLogger(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function myVideo($userId, $timestamp){
        $query = $this->connection->prepare("SELECT videoName, video.id, expire, video_user.id as vuid FROM "
                .$this->VIDEO_USER_DB
                ." inner join `user` on video_user.userId = `user`.id "
                . "inner join video on video_user.videoId = video.id "
                . " where userId = ? AND expire > ?");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("ii", $userId, $timestamp);
        $query->execute();
        $videos = $this->generateResult($query);
        return $videos;
    }

    public function totalMyVideo($userId, $timestamp){
        $query = $this->connection->prepare("SELECT count(video_user.id) FROM "
                .$this->VIDEO_USER_DB
                ." inner join `user` on video_user.userId = `user`.id "
                . "inner join video on video_user.videoId = video.id "
                . " where userId = ? AND expire > ?");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("ii", $userId, $timestamp);
        $query->execute();
        $query->bind_result($total);
        while($query->fetch()){
            return $total;
        }
    }
    
    public function isExist($userId, $videoId){
        $query = $this->connection->prepare("SELECT id FROM "
                .$this->VIDEO_USER_DB
                . " where userId = ? and  videoId = ?");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("ii", $userId, $videoId);
        $query->execute();
        $query->store_result();
        if($query->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function getToken($id){
        $query = $this->connection->prepare("SELECT token FROM "
                .$this->VIDEO_USER_DB
                . " where id = ?");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("i", $id);
        $query->execute();
        $videos = $this->generateResult($query);
        return $videos;
    }
}
