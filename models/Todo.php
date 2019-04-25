<?php
class Todo {
    private $conn;
    private $table = 'todos';

    public $id;
    public $user_id;
    public $title;
    public $completed;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function get() {
        $sql = 'SELECT * FROM '.$this->table.' WHERE id = :id AND user_id = :user_id';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->id);
        $query->bindParam('user_id', $this->user_id);
        $query->execute();
        $tmp = $query->fetch(PDO::FETCH_ASSOC);
        if ($tmp !== false) {
            return $tmp;
        }
        throw new PDOException('Post not found');
    }

    public function getAll() {
        $sql = 'SELECT * FROM '.$this->table.' WHERE user_id = :id ORDER BY id DESC';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->user_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLimit($limit) {
        $sql = 'SELECT * FROM '.$this->table.' WHERE user_id = :id ORDER BY id DESC LIMIT :limit';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->user_id);
        $query->bindParam('limit', $limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        try {
            $sql = 'INSERT INTO ' . $this->table . ' VALUES (NULL, :user_id, :title, false)';
            $query = $this->conn->prepare($sql);
            $query->bindParam('user_id', $this->user_id);
            $query->bindParam('title', $this->title);
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException('User ID is invalid');
        }
    }

    public function update() {
        $sql = 'UPDATE '.$this->table.' SET title = :title, completed = :completed WHERE id = :id AND user_id = :user_id';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->id, PDO::PARAM_INT);
        $query->bindParam('user_id', $this->user_id, PDO::PARAM_INT);
        $query->bindParam('title', $this->title, PDO::PARAM_STR);
        $query->bindParam('completed', $this->completed, PDO::PARAM_BOOL);
        $query->execute();
    }

    public function delete() {
        $sql = 'DELETE FROM '.$this->table.' WHERE id = :id AND user_id = :user_id';
        $query = $this->conn->prepare($sql);
        $query->bindParam('id', $this->id);
        $query->bindParam('user_id', $this->user_id);
        $query->execute();
    }
}