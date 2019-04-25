<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    require_once '../config/Database.php';
    require_once '../models/User.php';

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    try {
        $user->id = $database->ternary($data, 'id', 'ID is not set');
        $user->setUser();
    } catch (PDOException $e) {
        $database->send_json('', $e->getMessage());
        die();
    }

    $user->fname = isset($data->fname) ? $data->fname : $user->fname;
    $user->lname = isset($data->lname) ? $data->lname : $user->lname;
    $user->password = isset($data->password) ? $data->password : $user->password;
    $user->active = isset($data->active) ? $data->active : $user->active;
    $user->update();
    $database->send_json('User updated successfully', '');