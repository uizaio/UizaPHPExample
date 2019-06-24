<?php

require __DIR__."/../vendor/autoload.php";
require_once "../config/config.php";
require_once '../lib/SendRequest.php';
require_once "../model/VideoDB.php";

use model\VideoDB;

//Uiza\Base::setAuthorization("uap-52d2872e914e46acaa00c854fae1c537-6c86f722");


$method = $_SERVER["REQUEST_METHOD"];
date_default_timezone_set("Asia/Ho_Chi_Minh");

switch ($method){
    case "POST":
        $connection = new SendRequest("https://trungprod015-api.uiza.co/api/public/v3/media/entity");
        $headers = array();
        array_push($headers, "Content-Type: application/json");
        array_push($headers, "Authorization: uap-01e137ad1b534004ad822035bf89b29f-b9b31f29");
        $connection->createConnectionWithNoParam();
        $connection->setCustomHeaders($headers);
        $resp = $connection->sendRequest("", "GET");
        $resp = json_decode($resp);
        $entities = $resp->data;
        $db = new VideoDB();
        foreach ($entities as $entity){
            $id = $entity->id;
            $isExist = $db->isVideoExist($id);
            if(!$isExist){
                $videoName = $entity->name;
                $db->insert($id, $videoName);
            }
        }
        $db->dbClose();
        $response = array("res" => $entities);
        echo json_encode($response);
        break;
    case "GET":
        http_response_code(405);
        echo "Get method is invalid!";
        break;
}
