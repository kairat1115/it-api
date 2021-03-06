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

    try {
        $user->email = $database->ternary($data, 'email', 'Email is not set');
        $user->password = $database->ternary($data, 'password', 'Password is not set');
        $database->send_json($user->check(), '');
    } catch (PDOException $e) {
        $database->send_json('', $e->getMessage());
    }

