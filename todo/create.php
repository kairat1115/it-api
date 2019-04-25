<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require_once '../config/Database.php';
require_once '../models/Todo.php';

$database = new Database();
$db = $database->connect();

$todo = new Todo($db);

$data = json_decode(file_get_contents("php://input"));

try {
    $todo->user_id = $database->ternary($data, 'user_id', 'User ID is not set');
    $todo->title = $database->ternary($data, 'title', 'Title is not set');
    $todo->create();
    $todo->id = $db->lastInsertId();
    $database->send_json($todo->get(), '');
} catch (PDOException $e) {
    $database->send_json('', $e->getMessage());
}