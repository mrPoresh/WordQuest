<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';

class GameController {
    
    private $pdo;
    
    private $authController;
    private $userController;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        $this->authController = new AuthController($this->pdo);
        $this->userController = new UserController($this->pdo);
    }

    public function createGame($userId, $wordLength, $maxAttempts) {
        list($winScore, $minWinScore, $looseScore) = $this->calculatePoints($wordLength, $maxAttempts);

        $tableName = "words_" . $wordLength;
        $stmt = $this->pdo->query("SELECT word FROM $tableName ORDER BY RAND() LIMIT 1");
        $secretWord = $stmt->fetchColumn();

        $sql = "INSERT INTO games (user_id, secret_word, max_attempts, max_win_score, loose_score) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$userId, $secretWord, $maxAttempts, $winScore, $looseScore]);
    }

    public function endActiveGame($userId) {
        $stmt = $this->pdo->prepare('UPDATE games SET status = ? WHERE user_id = ? AND status = ?');
        $stmt->execute(['lost', $userId, 'in_progress']);

        $stmt = $this->pdo->prepare('SELECT loose_score FROM games WHERE user_id = ? AND status = ? ORDER BY end_time DESC LIMIT 1');
        $stmt->execute([$userId, 'lost']);
        $game = $stmt->fetch();

        if ($game) {
            $looseScore = -$game['loose_score'];
            return $this->updateUserScore($userId, $looseScore);
        }

        return false;
    }

    public function loadGame($userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM games WHERE user_id = ? AND status = ?');
        $stmt->execute([$userId, 'in_progress']);
        
        return $stmt->fetch();
    }

    private function updateUserScore($userId, $scoreChange) {
        $stmt = $this->pdo->prepare('UPDATE scores SET current_score = current_score + ?, last_score = ? WHERE user_id = ?');
        return $stmt->execute([$scoreChange, $scoreChange, $userId]);
    }

    private function calculatePoints($wordLength, $attempts) {
        $baseScore = $wordLength * 10;
        $maxWinScore = $baseScore * 10;
        $minWinScore = $maxWinScore - $baseScore * ($attempts - 3);
        $looseScore = $maxWinScore / 2;

        return [$maxWinScore, $minWinScore, $looseScore];
    }

    // public function createGame($wordLength, $token) {
    //     $userId = $this->authController->verifyToken($token);

    //     if (!$userId) {
    //         return false;
    //     }

    //     // Check if there is an active game and mark it as lost
    //     $stmt = $this->pdo->prepare('UPDATE games SET status = "lost", end_time = NOW() WHERE user_id = ? AND status = "in_progress"');
    //     $stmt->execute([$userId]);

    //     // Select a random word from the appropriate table
    //     $stmt = $this->pdo->query("SELECT word FROM words_{$wordLength} ORDER BY RAND() LIMIT 1");
    //     $word = $stmt->fetchColumn();

    //     // Create a new game
    //     $stmt = $this->pdo->prepare('INSERT INTO games (user_id, secret_word) VALUES (?, ?)');
    //     $stmt->execute([$userId, $word]);

    //     return true;
    // }

    // public function loadGame($token, $username) {
    //     $userId = $this->authController->verifyToken($token);
    //     if (!$userId) {
    //         return false; // Invalid token
    //     }

    //     // Retrieve username from database
    //     $stmt = $this->pdo->prepare('SELECT username FROM users WHERE id = ?');
    //     $stmt->execute([$userId]);
    //     $dbUsername = $stmt->fetchColumn();

    //     if ($username !== $dbUsername) {
    //         return false; // Username does not match
    //     }

    //     // Retrieve the last game with status 'in_progress'
    //     $stmt = $this->pdo->prepare('SELECT * FROM games WHERE user_id = ? AND status = "in_progress" ORDER BY start_time DESC LIMIT 1');
    //     $stmt->execute([$userId]);
    //     $game = $stmt->fetch();

    //     if (!$game) {
    //         return false; // No active game found
    //     }

    //     return $game;
    // }

    // public function sendAttempt($token, $username, $attemptWord) {
    //     $userId = $this->authController->verifyToken($token);
    //     if (!$userId) {
    //         return false; // Invalid token
    //     }

    //     $stmt = $this->pdo->prepare('SELECT username FROM users WHERE id = ?');
    //     $stmt->execute([$userId]);
    //     $dbUsername = $stmt->fetchColumn();

    //     if ($username !== $dbUsername) {
    //         return false; // Username does not match
    //     }

    //     $stmt = $this->pdo->prepare('SELECT * FROM games WHERE user_id = ? AND status = "in_progress"');
    //     $stmt->execute([$userId]);
    //     $game = $stmt->fetch();

    //     if (!$game) {
    //         return false; // No active game found
    //     }

    //     $stmt = $this->pdo->prepare('SELECT * FROM attempts WHERE game_id = ?');
    //     $stmt->execute([$game['id']]);
    //     $attempts = $stmt->fetchAll();

    //     if (count($attempts) >= 5) {
    //         return "Maximum number of attempts reached"; // Error
    //     }

    //     $stmt = $this->pdo->prepare('INSERT INTO attempts (game_id, attempt_word) VALUES (?, ?)');
    //     $stmt->execute([$game['id'], $attemptWord]);

    //     if ($attemptWord === $game['secret_word']) {
    //         $stmt = $this->pdo->prepare('UPDATE games SET status = "won", end_time = NOW() WHERE id = ?');
    //         $stmt->execute([$game['id']]);

    //         $stmt = $this->pdo->prepare('DELETE FROM attempts WHERE game_id = ?');
    //         $stmt->execute([$game['id']]);

    //         return "Congratulations! You've guessed the word!";
    //     } else if (count($attempts) + 1 >= 5) {
    //         $stmt = $this->pdo->prepare('UPDATE games SET status = "lost", end_time = NOW() WHERE id = ?');
    //         $stmt->execute([$game['id']]);

    //         return "Game over! You've used all attempts.";
    //     } else {
    //         $feedback = [];
    //         for ($i = 0; $i < strlen($attemptWord); $i++) {
    //             if ($attemptWord[$i] === $game['secret_word'][$i]) {
    //                 $feedback[] = 2; // Correct position
    //             } else if (strpos($game['secret_word'], $attemptWord[$i]) !== false) {
    //                 $feedback[] = 1; // Correct letter, wrong position
    //             } else {
    //                 $feedback[] = 0; // Incorrect letter
    //             }
    //         }
    //         return $feedback; // Return feedback array for the attempt
    //     }
    // }

    // public function handleAttemptRequest() {
    //     header('Content-Type: application/json');

    //     $data = json_decode(file_get_contents('php://input'), true);

    //     $token = $data['token'];
    //     $username = $data['username'];
    //     $attempt_word = $data['attempt_word'];

    //     $response = $this->sendAttempt($token, $username, $attempt_word);

    //     echo json_encode($response);
    // }
}

?>