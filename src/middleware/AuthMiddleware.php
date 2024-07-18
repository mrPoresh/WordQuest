<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class AuthMiddleware {
    private $pdo;
    private $authController;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->authController = new AuthController($pdo);
    }

    public function checkSession() {
        if (isset($_SESSION['auth_token'])) {
            $userId = $this->authController->verifyToken($_SESSION['auth_token']);
            
            if ($userId) {
                return $userId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkAuthHeader() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            echo json_encode(['success' => false, 'error' => 'Authorization header not found']);
            exit();
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        list($type, $token) = explode(' ', $authHeader);

        if ($type !== 'Bearer') {
            echo json_encode(['success' => false, 'error' => 'Invalid authorization header format']);
            exit();
        }

        return $this->authController->verifyToken($token);
    }
}

?>