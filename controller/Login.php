<?php

header("Access-Control-Allow-Origin: http://localhost:8000/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../vendor/autoload.php';
require_once "../config/config.php";
require_once "../model/UserDB.php";

use model\UserDB;
use \Firebase\JWT\JWT;

date_default_timezone_set("Asia/Ho_Chi_Minh");
$date = new DateTime();
$timestamp = $date->getTimestamp();
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$password = $data->password;
$db = new UserDB();
$email_exist = $db->emailExist($email);
if($email_exist){
    $user = $db->getUser($email);
    if(sizeof($user > 0)){
        $user = $user[0];
        
        password_verify($password, $user["password"]);
        $iat = $timestamp;
    //    $nbf = $timestamp;
        $exp = $timestamp + (60 * 60); //one hour
        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat, //issuer time
    //        "nbf" => $nbf, //not before
            "exp" => $exp, //expire time
            "data" => array(
                "id" => $user["id"],
                "firstname" => $user["firstname"],
                "lastname" => $user["lastname"],
                "email" => $email
            )
        );   
        http_response_code(200);
        $jwt = JWT::encode($token, $key);
        $response = array("message" => "Successful login.", "jwt" => $jwt);
        $db->dbClose();
        echo json_encode($response);
    }else{
        http_response_code(401);
        $response = array("message" => "Login failed");
        $db->dbClose();
        echo json_encode($response);
    }
    
}else{
    http_response_code(401);
    $response = array("message" => "Login failed");
    $db->dbClose();
    echo json_encode($response);
}

