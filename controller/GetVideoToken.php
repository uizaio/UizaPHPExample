<?php

require_once "../config/config.php";
require_once __DIR__ . '/../vendor/autoload.php';
require_once "../model/UserVideoDB.php";

use model\UserVideoDB;
use \Firebase\JWT\JWT;

$method = $_SERVER["REQUEST_METHOD"];
date_default_timezone_set("Asia/Ho_Chi_Minh");

switch ($method){
    case "POST":
        $request = file_get_contents("php://input");
        $oJson = json_decode($request);
        $id = $oJson->id;
        $jwt = isset($oJson->jwt) ? $oJson->jwt : "";
        if($jwt){
            try{
                $decode = JWT::decode($jwt, $key, array("HS256"));
                http_response_code(200);                
                $db = new UserVideoDB();
                $result = $db->getToken($id);
                $response = array("res" => $result);
                $db->dbClose();
                echo json_encode($response);
            }catch(Exception $ex){
                http_response_code(401);
                $response = array("message" => "Access denied.", "error" => $ex->getMessage());
                echo json_encode($response);
            }
        }
        
        break;
    case "GET":
        http_response_code(405);
        echo "Get method is invalid!";
        break;
}
