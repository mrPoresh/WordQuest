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

        $sql = "INSERT INTO games (user_id, secret_word, word_length, attempts, max_attempts, max_win_score, loose_score) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$userId, $secretWord, $wordLength, 0, $maxAttempts, $winScore, $looseScore]);
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
        // Start a transaction
        $this->pdo->beginTransaction();
        
        try {
            // Get the current score
            $stmt = $this->pdo->prepare('SELECT current_score FROM scores WHERE user_id = ?');
            $stmt->execute([$userId]);
            $currentScore = $stmt->fetchColumn();
    
            // Update the scores
            $stmt = $this->pdo->prepare('UPDATE scores SET current_score = current_score + ?, last_score = ? WHERE user_id = ?');
            $result = $stmt->execute([$scoreChange, $currentScore, $userId]);
    
            // Commit the transaction
            $this->pdo->commit();
            
            return $result;
        } catch (Exception $e) {
            // Rollback the transaction if something failed
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function calculatePoints($wordLength, $attempts) {
        $baseScore = $wordLength * 10;
        $maxWinScore = $baseScore * 10;
        $minWinScore = $maxWinScore - $baseScore * ($attempts - 3);
        $looseScore = $maxWinScore / 2;

        return [$maxWinScore, $minWinScore, $looseScore];
    }

    private function calculateWinScore($wordLength, $attempts) {
        $baseScore = $wordLength * 10;
        $maxWinScore = $baseScore * 10;

        if ($attempts <= 4) {
            $attempts = 3;
        }

        $score = $maxWinScore - $baseScore * ($attempts - 3);
    
        return $score;
    }

    public function addAttempt($userId, $gameId, $guess) {
        $stmt = $this->pdo->prepare('SELECT secret_word, attempts, max_attempts, loose_score FROM games WHERE id = ? AND user_id = ?');
        $stmt->execute([$gameId, $userId]);
        $game = $stmt->fetch();
    
        if (!$game) {
            return ['success' => false, 'error' => 'Game not found'];
        }
    
        if ($game['attempts'] >= $game['max_attempts']) {
            return ['success' => false, 'error' => 'Maximum attempts reached'];
        }
    
        $secretWord = $game['secret_word'];
        $wordLength = strlen($secretWord);
        $attempts = $game['attempts'] + 1;
    
        $feedback = [];
        for ($i = 0; $i < $wordLength; $i++) {
            if ($guess[$i] == $secretWord[$i]) {
                $feedback[$i] = 2; // Correct position and letter
            } elseif (strpos($secretWord, $guess[$i]) !== false) {
                $feedback[$i] = 1; // Correct letter, wrong position
            } else {
                $feedback[$i] = 0; // Wrong letter
            }
        }
    
        $status = 'in_progress';
    
        if (implode('', $guess) === $secretWord) {
            $status = 'won';
            $winScore = $this->calculateWinScore($wordLength, $attempts); // Calculate win score
            $this->updateUserScore($userId, $winScore);
        } elseif ($attempts >= $game['max_attempts']) {
            $status = 'lost';
            $this->updateUserScore($userId, -$game['loose_score']);
        }

        if ($status != 'in_progress') {
            $stmt = $this->pdo->prepare('DELETE FROM attempts WHERE game_id = ?');
            $stmt->execute([$gameId]);
        } else {
            // Save attempt with feedback
            $stmt = $this->pdo->prepare('INSERT INTO attempts (game_id, attempt_word, feedback) VALUES (?, ?, ?)');
            $stmt->execute([$gameId, implode('', $guess), implode('', $feedback)]);
        }
    
        // Update game status and attempts count
        $stmt = $this->pdo->prepare('UPDATE games SET attempts = ?, status = ? WHERE id = ?');
        $stmt->execute([$attempts, $status, $gameId]);
    
        return ['feedback' => $feedback, 'status' => $status];
    }

    public function getAttemptsWithFeedback($gameId) {
        $stmt = $this->pdo->prepare('SELECT attempt_word, feedback FROM attempts WHERE game_id = ? ORDER BY id ASC');
        $stmt->execute([$gameId]);
        $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($attempts as $attempt) {
            $attempt_word = str_split($attempt['attempt_word']);
            $feedback = str_split($attempt['feedback']);
            $result[] = ['attempt_word' => $attempt_word, 'feedback' => $feedback];
        }

        return $result;
    }

}

?>