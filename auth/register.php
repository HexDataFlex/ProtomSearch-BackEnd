<?php

include_once '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input", true));

Auth::register($data->username, $data->email, $data->password1, $data->password2);
