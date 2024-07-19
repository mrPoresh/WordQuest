<?php

require_once '../../../src/config/session.php';
require_once '../../../src/config/database.php';
require_once '../../../src/controllers/AuthController.php';
require_once '../../../src/controllers/GameController.php';
require_once '../../../src/middleware/AuthMiddleware.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $guess = $data['guess'];

    $authMiddleware = new AuthMiddleware($pdo);
    $userId = $authMiddleware->checkAuthHeader();

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired token']);
        exit();
    }

    $gameController = new GameController($pdo);
    $activeGame = $gameController->loadGame($userId);

    if (!$activeGame) {
        echo json_encode(['success' => false, 'error' => 'No active game found']);
        exit();
    }

    $result = $gameController->addAttempt($userId, $activeGame['id'], $guess);

    echo json_encode(['success' => true, 'result' => $result]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

?>