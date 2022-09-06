<?php

include_once '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input", true));

if (!(isset($data->username) || isset($data->email)) && !isset($data->password)) {
    echo json_encode(array("error" => "Please fill out the username or email address and password"));
    http_response_code(400);
    exit();
}

if (isset($data->username)) {
    Auth::loginWithUsername($data->username, $data->password);
} else if (isset($data->email)) {
    Auth::loginWithEmail($data->email, $data->password);
}
