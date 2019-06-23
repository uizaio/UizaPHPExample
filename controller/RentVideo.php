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
        $videoId = $oJson->id;
        $jwt = isset($oJson->jwt) ? $oJson->jwt : "";
        $expire = $oJson->expire;
//        https://php.net/manual/en/datetime.createfromformat.php
        $dtime = DateTime::createFromFormat("Y-m-d", $expire);
        $timestamp = $dtime->getTimestamp();
        if($jwt){
            try{
                $decode = JWT::decode($jwt, $key, array("HS256"));
                http_response_code(200);
                
                $db = new UserVideoDB();
                //we must create jwt in here
                $email = $decode->data->email;
                $userId = $decode->data->id;
                $date = new DateTime();
                $iat = $date->getTimestamp();
                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat, //issuer time
                    "exp" => $timestamp, //expire time
                    "data" => array(
                        "id" => $userId,
                        "email" => $email,
                        "video" => $videoId
                    )
                );  
                $jwt = JWT::encode($token, $key);
                $result = $db->insert($videoId, $userId, $jwt, $timestamp);
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
