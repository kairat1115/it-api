<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'api';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: '.$e->getMessage();
        }
        return $this->conn;
    }

    public function send_json($message, $error) {
        echo json_encode(array(
            'output'=>array(
                'message'=>$message
            ),
            'error'=>$error
        ));
    }

    public function ternary($obj, $prop, $errorMsg) {
        if (isset($obj->$prop)) {
            return $obj->$prop;
        }
        throw new PDOException($errorMsg, 1337);
    }
}