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
        $user->fname = $database->ternary($data, 'fname', 'First name is not set');
        $user->lname = $database->ternary($data, 'lname', 'Last name is not set');
        $user->email = $database->ternary($data, 'email', 'Email is not set');
        $user->password = $database->ternary($data, 'password', 'Password is not set');
        $user->active = true;
        $user->create();
        $database->send_json('User is created', '');
    } catch (PDOException $e) {
        $database->send_json('', 'User is not created');
    }