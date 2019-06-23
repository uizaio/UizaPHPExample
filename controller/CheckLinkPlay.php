<?php

require_once "../config/config.php";
require_once __DIR__ . '/../vendor/autoload.php';
require_once "../model/UserVideoDB.php";
require_once "../model/VideoDB.php";

use \Firebase\JWT\JWT;
use model\UserVideoDB;
use model\VideoDB;

$method = $_SERVER["REQUEST_METHOD"];
date_default_timezone_set("Asia/Ho_Chi_Minh");

switch ($method){
    case "POST":
        $request = file_get_contents("php://input");
        $oJson = json_decode($request);
        $jwt = isset($oJson->token) ? $oJson->token : "";
        if($jwt){
            try{
                $decode = JWT::decode($jwt, $key, array("HS256"));
                $email = $decode->data->email;
                $userId = $decode->data->id;
                $videoId = $decode->data->video;
                $db = new UserVideoDB();
                $isExist = $db->isExist($userId, $videoId);
                $response;
                if(!$isExist){
                    http_response_code(401);
                    $response = array("message" => "Access denied.", "error" => "Video does not exist!");
                }else{
                    $db1 = new VideoDB();
                    $video = $db1->getVideo($videoId);
                    $response = array("status" => "success", "res" => $video);
                    $db1->dbClose();
                }
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

