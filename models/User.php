<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $fname;
    public $lname;
    public $email;
    public $password;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $sql = 'INSERT INTO '.$this->table.' VALUES (NULL, :fname, :lname, :email, :password, :active)';
        $query = $this->conn->prepare($sql);
        $query->bindParam('fname', $this->fname);
        $query->bindParam('lname', $this->lname);
        $query->bindParam('email', $this->email);
        $query->bindParam('password', $this->password);
        $query->bindParam('active', $this->active);
        return $query->execute();
    }

    public function get() {
        $sql = 'SELECT id, fname, lname, active FROM '.$this->table.' WHERE email = :email';
        $query = $this->conn->prepare($sql);
        $query->bindParam('email', $this->email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function setUser() {
        $sql = 'SELECT * FROM '.$this->table.' WHERE id = :id';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->id);
        $query->execute();
        $tmp = $query->fetch(PDO::FETCH_ASSOC);
        if (isset($tmp)) {
            $this->email = $tmp['email'];
            $this->fname = $tmp['fname'];
            $this->lname = $tmp['lname'];
            $this->password = $tmp['password'];
            $this->active = $tmp['active'];
            return;
        }
        throw new PDOException('User not found', 1337);
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

    public function update() {
        $sql = 'UPDATE ' . $this->table . ' SET fname = :fname, lname = :lname, password = :password, active = :active WHERE email = :email';
        $query = $this->conn->prepare($sql);
        $query->bindParam('email', $this->email);
        $query->bindParam('fname', $this->fname);
        $query->bindParam('lname', $this->lname);
        $query->bindParam('password', $this->password);
        $query->bindParam('active', $this->active);
        $query->execute();
    }
}