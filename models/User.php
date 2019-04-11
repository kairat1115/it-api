<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $fname;
    public $lname;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $sql = 'INSERT INTO '.$this->table.' VALUES (NULL, :fname, :lname, :email, :password)';
        $query = $this->conn->prepare($sql);
        $query->bindParam('fname', $this->fname);
        $query->bindParam('lname', $this->lname);
        $query->bindParam('email', $this->email);
        $query->bindParam('password', $this->password);
        try {
            $query->execute();
            return '';
        } catch (PDOException $e) {
            return sprintf("Error: %s", $e->getMessage());
        }
    }

    public function get() {
        $sql = 'SELECT fname, lname FROM '.$this->table.' WHERE email = :email AND password = :password';
        $query = $this->conn->prepare($sql);
        $query->bindParam('email', $this->email);
        $query->bindParam('password', $this->password);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function check() {
        $sql = 'SELECT EXISTS(SELECT * FROM '.$this->table.' WHERE email = :email AND password = :password) AS result';
        $query = $this->conn->prepare($sql);
        $query->bindParam('email', $this->email);
        $query->bindParam('password', $this->password);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC)['result'];
        if ($result == '0')
            return 'Not found';
        return $this->get();
    }
}