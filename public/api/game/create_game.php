<?php

require_once '../../../src/config/session.php';
require_once '../../../src/config/database.php';
require_once '../../../src/controllers/AuthController.php';
require_once '../../../src/controllers/GameController.php';
require_once '../../../src/middleware/AuthMiddleware.php';

header('Content-Type: application/json');


//  calculate score on both sides
if ($_SERVER['REQUEST_METHOD'] == 'POST') {     //  TODO: Add input value for attmpts.
    $data = json_decode(file_get_contents('php://input'), true);
    $wordLength = $data['wordLength'];
    $maxAttempts = $data['maxAttempts'];
    
    if ($wordLength < 4 || $wordLength > 8 || $maxAttempts < 3 || $maxAttempts > 8) {
        echo json_encode(['success' => false, 'error' => 'Invalid word length or max attempts']);
        exit();
    }

    $authMiddleware = new AuthMiddleware($pdo);
    $userId = $authMiddleware->checkAuthHeader();

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired token']);
        exit();
    }

    $gameController = new GameController($pdo);
    $gameStatus = $gameController->createGame($userId, $wordLength, $maxAttempts);

    if ($gameStatus) {
        echo json_encode(['success' => true, 'game_status' => $gameStatus]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to create a new game']);
    }
   
    exit();

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

?>
