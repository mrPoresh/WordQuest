<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';

class UserController {

    private $pdo;
    private $authController;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->authController = new AuthController($this->pdo);
    }

    public function addUserScore($userId) {
        $sql = "INSERT INTO scores (user_id) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$userId]);
    }

    public function getUserScore($userId): ?array {
        $sql = "SELECT * FROM scores WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        $score = $stmt->fetch();
        return $score ?: null;
    }

    public function getUserById($userId): ?array {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        $user = $stmt->fetch();
        return $user ?: null;
    }
}

?>