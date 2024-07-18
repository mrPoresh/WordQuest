<?php

require_once '../../../src/config/session.php';
require_once '../../../src/config/database.php';
require_once '../../../src/controllers/AuthController.php';
require_once '../../../src/controllers/UserController.php';
require_once '../../../src/middleware/AuthMiddleware.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $authMiddleware = new AuthMiddleware($pdo);
    $userId = $authMiddleware->checkAuthHeader();

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired token']);
        exit();
    }

    $userController = new UserController($pdo);
    $user = $userController->getUserById($userId);
    $score = $userController->getUserScore($userId);

    if ($user) {
        echo json_encode([
            'success' => true, 
            'user' => [
                'username' => $user['username'],
                'email' => $user['email'],
                'created_at' => $user['created_at']
            ],
            'score' => [
                'current_score' => $score['current_score'],
                'last_score' => $score['current_score'],
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found']);
    }

    exit();

}  else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

?>