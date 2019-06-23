<?php

/**
 * Description of SendRequest
 *
 * @author duc
 */
class SendRequest {
    
    private $connection;
    private $uri;
    
    Function __construct($uri) {
        $this->uri = $uri;
//        $this->createConnection($this->uri);
    }
    
    /**
     * Set custom header for connection
     * 
     * @param array $headers
     */
    public function setCustomHeaders($headers){
        curl_setopt($this->connection, CURLOPT_HTTPHEADER, $headers);
    }
    
    
    public function createConnection($uri){
        $this->connection = curl_init($uri);
        curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, "POST");
        
    }
    
    public function createConnectionWithNoParam(){
        $this->connection = curl_init($this->uri);
    }
    
    public function sendRequest($request, $method = "POST"){
//        $this->createConnection($this->uri);
        curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $request);
        curl_setopt($this->connection,  CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($this->connection);
        return $result;
    }
    
    public Function closeConnection(){
        curl_close($this->connection);
    }
}
