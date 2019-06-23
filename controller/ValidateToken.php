<?php

header("Access-Control-Allow-Origin: http://localhost:8000/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../vendor/autoload.php';
require_once "../config/config.php";
use \Firebase\JWT\JWT;


$data = json_decode(file_get_contents("php://input"));
$jwt = isset($data->jwt) ? $data->jwt : "";
if($jwt){
    try{
        $decode = JWT::decode($jwt, $key, array("HS256"));
        http_response_code(200);
        $response = array("message" => "Access granted.", "data" => $decode->data);
        //dispatch request to some where
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
