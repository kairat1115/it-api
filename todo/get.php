<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require_once '../config/Database.php';
require_once '../models/Todo.php';

$database = new Database();
$db = $database->connect();

$todo = new Todo($db);

try {
    if (!isset($_GET['user_id']))
        throw new PDOException('User ID is not set');
    $todo->user_id = $_GET['user_id'];
    $response = null;
    if (isset($_GET['limit']) && is_numeric($_GET['limit']) && intval($_GET['limit']) > 0) {
        $response = $todo->getAllLimit($_GET['limit']);
    }
    else {
        $response = $todo->getAll();
    }
    $database->send_json($response, '');
} catch (PDOException $e) {
    $database->send_json('', $e->getMessage());
}