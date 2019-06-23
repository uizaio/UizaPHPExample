<?php
require_once __DIR__ .'/../config/functions.php';
/**
 * Description of Database
 *
 * @author duc
 */
class Database {
    
    
    Function __construct($is_manual_connect = false) {
//        $this->db_username = $username;
//        $this->db_host = $host;
//        $this->db_password = $password;
//        $this->db_dbname = $dbname;
        $this->dbConnect();
    }
    
    protected $USER_DB = "`user`";
    protected $VIDEO_DB = "`video`";
    protected $VIDEO_USER_DB = "`video_user`";


    public function dbConnectWithArg($host, $user, $password, $database){
        if($this->connection == true){
            $this->connection->close();
        }
        $this->connection = new mysqli($host, $user, $password, $database);
        if($this->connection->connect_errno){
            errorLogger("Could not connect to database server:".$this->connection->connect_errno, "error");
            die("Could not connect to database server");
        }
        $this->connection->set_charset("utf8");
    }
    
    /*
     * Ket noi toi co so du lieu
     */
    private Function dbConnect(){
        
//        $this->connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_dbname);
        $this->connection = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE);
        if($this->connection->connect_errno){
            errorLogger("Could not connect to database server:".$this->connection->connect_errno, "error");
            die("Could not connect to database server");
        }
        $this->connection->set_charset("utf8");
    }
    
    public function getConnection(){
        return $this->connection;
    }
    
    public Function dbClose(){
        $this->connection->close();
    }
    
    /**
     * Get created at value for tables
     * @return type
     */
    protected function getCreatedDate(){
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $now = new \DateTime();
        return $now->format("Y-m-d H:i:s");
    }
    
    //http://code.tutsplus.com/tutorials/the-problem-with-phps-prepared-statements--net-13661
    /**
     * 
     * @param type $inputArray
     * @param type $query
     * @return type
     */
    protected function generateResult($query){
        $dataArray = array();
        $meta = $query->result_metadata();
        while ($field = $meta->fetch_field()){
            
            $params[] = &$row[$field->name];
        } 
        call_user_func_array(array($query, 'bind_result'), $params);
        while ($query->fetch()) {
            foreach($row as $key => $val){
                $c[$key] = $val;
            }
            $dataArray[] = $c;
        }
        $query->close();
        return $dataArray;
    }
}
