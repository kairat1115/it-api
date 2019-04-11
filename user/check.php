<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    require_once '../config/Database.php';
    require_once '../models/User.php';

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if ($data == null) {
        $database->send_json('', 'email and password must be specified');
        die();
    }

    $user->email = $data->email;
    $user->password = $data->password;

    $database->send_json($user->check(), '');