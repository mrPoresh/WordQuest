<?php

require_once '../../../src/config/session.php';
require_once '../../../src/config/database.php';
require_once '../../../src/controllers/AuthController.php';
require_once '../../../src/controllers/GameController.php';
require_once '../../../src/middleware/AuthMiddleware.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authMiddleware = new AuthMiddleware($pdo);
    $userId = $authMiddleware->checkAuthHeader();

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired token']);
        exit();
    }

    $gameController = new GameController($pdo);
    
    if ($gameController->endActiveGame($userId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to end the active game']);
    }

    exit();

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

?>