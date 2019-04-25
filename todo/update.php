<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require_once '../config/Database.php';
require_once '../models/Todo.php';

$database = new Database();
$db = $database->connect();

$todo = new Todo($db);

$data = json_decode(file_get_contents("php://input"));

try {
    if (!(isset($_GET['user_id']) && is_numeric($_GET['user_id'])))
        throw new PDOException('User ID is not set');
    if (!(isset($_GET['id']) && is_numeric($_GET['id'])))
        throw new PDOException('TODO ID is not set');
    $todo->user_id = $_GET['user_id'];
    $todo->id = $_GET['id'];
    $tmp = $todo->get();
    $todo->title = $tmp['title'];
    $todo->completed = $tmp['completed'];
} catch (PDOException $e) {
    $database->send_json('', $e->getMessage());
    die();
}

$todo->title = isset($data->title) ? $data->title : $todo->title;
$todo->completed = isset($data->completed) ? $data->completed : $todo->completed;

$todo->update();
$database->send_json($todo->get(), '');