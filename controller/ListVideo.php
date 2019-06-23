<?php

require_once "../config/config.php";
require_once __DIR__ . '/../vendor/autoload.php';
require_once "../model/VideoDB.php";


use model\VideoDB;
use \Firebase\JWT\JWT;

$method = $_SERVER["REQUEST_METHOD"];
date_default_timezone_set("Asia/Ho_Chi_Minh");

switch ($method){
    case "POST":
        $request = file_get_contents("php://input");
        $oJson = json_decode($request);
        $jwt = isset($oJson->jwt) ? $oJson->jwt : "";
        if($jwt){
            try{
                $decode = JWT::decode($jwt, $key, array("HS256"));
                http_response_code(200);
                $pageIndex = 0;
                $pageSize = 20;
                $db = new VideoDB();
                $videos = $db->listVideo($pageIndex, $pageSize);
                $total = $db->totalVideo();
                $db->dbClose();
                $result = array();
                foreach($videos as $video){
                    $video["recid"] = $video["id"];                    
                    array_push($result, $video);
                }
                $response = array("status" => "success", "total" => $total, "records" => $result);
                echo json_encode($response);
            }catch(Exception $ex){
                http_response_code(401);
                $response = array("message" => "Access denied.", "error" => $ex->getMessage());
                echo json_encode($response);
            }
        }else{
            http_response_code(401);
            $response = array("message" => "Access denied.");
            echo json_encode($response);
        }        
        break;
    case "GET":
        http_response_code(405);
        echo "Get method is invalid!";
        break;
}

