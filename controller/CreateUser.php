<?php

header("Access-Control-Allow-Origin: http://localhost:8000/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../config/config.php";
require_once "../model/UserDB.php";

use model\UserDB;

$data = json_decode(file_get_contents("php://input"));

$firstname = $data->firstname;
$lastname = $data->lastname;
$email = $data->email;
$password = $data->password;
$db = new UserDB();
if(!empty($firstname) && !empty($email) && !empty($password)){
    $result = $db->create($firstname, $lastname, $email, $password);
    http_response_code(200);
    $response = array("message" => "User was created.");
    echo json_encode($response);
}else{
    http_response_code(400);
    $response = array("message" => "Unable to create user.");
    echo json_encode($response);
}