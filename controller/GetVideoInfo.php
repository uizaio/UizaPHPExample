<?php

require_once "../config/config.php";
require_once "../model/VideoDB.php";

use model\VideoDB;


$method = $_SERVER["REQUEST_METHOD"];
date_default_timezone_set("Asia/Ho_Chi_Minh");

switch ($method){
    case "POST":
        $request = file_get_contents("php://input");
        $oJson = json_decode($request);
        $id = $oJson->id;
        $db = new VideoDB();
        $video = $db->getVideo($id);
        $response = array("res" => $video);
        echo json_encode($response);
        break;
    case "GET":
        http_response_code(405);
        echo "Get method is invalid!";
        break;
    
}
