<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/GameController.php';
require_once '../src/middleware/AuthMiddleware.php';


$authMiddleware = new AuthMiddleware($pdo);
$userId = $authMiddleware->checkSession();

if (!$userId) {
    header('Location: login.php');
    exit();
}

$gameController = new GameController($pdo);
$activeGame = $gameController->loadGame($userId);

error_log($activeGame['id']);

if (!$activeGame) {
    header('Location: game_settings.php');
    exit();
}

// error_log("Game Data: max_attempts => {$activeGame['max_attempts']}, word_length => {$activeGame['word_length']}, attempts => {$activeGame['attempts']}");

$attempts = $gameController->getAttemptsWithFeedback($activeGame['id']);
$wordLength = $activeGame['word_length'];

$title = "Game";
ob_start();

?>

<div class="game-wrapper">
    <div class="game-container">
        <form id="gameForm">
            <div class="game-grid-container">
                <div class="grid" style="grid-template-columns: repeat(<?php echo $wordLength; ?>, 1fr);">
                    <?php for ($i = 0; $i < $activeGame['max_attempts']; $i++): ?>
                        <?php for ($j = 0; $j < $wordLength; $j++): ?>
                            <?php
                                $value = isset($attempts[$i]['attempt_word'][$j]) ? htmlspecialchars($attempts[$i]['attempt_word'][$j]) : '';
                                $disabled = ($i > $activeGame['attempts'] || $i < $activeGame['attempts']) ? 'disabled' : '';
                                $class = '';
                                if (isset($attempts[$i]['feedback'][$j])) {
                                    switch ($attempts[$i]['feedback'][$j]) {
                                        case 2:
                                            $class = 'correct';
                                            break;
                                        case 1:
                                            $class = 'misplaced';
                                            break;
                                        case 0:
                                            $class = 'incorrect';
                                            break;
                                    }
                                }
                            ?>
                            <input type="text" name="guess[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $value; ?>" <?php echo $disabled; ?> class="<?php echo $class; ?>" maxlength="1" required>
                        <?php endfor; ?>
                    <?php endfor; ?>
                </div>
            </div>

            <button class="btn-round medium primary" type="submit"><h4>Submit Guess</h4></button>
        </form>

        <div class="game-action-container">
            <h3>Game Stats</h3>
            <br>
            <h4>Maximum points:</h4>
            <h4>Current points:</h4>
        </div>
    </div>
</div>

<!-- <div class="game-wrapper">
    <form id="gameForm">
        <div class="game-grid-container">
            <div class="grid" style="grid-template-columns: repeat(<?php echo $wordLength; ?>, 1fr);">
                <?php for ($i = 0; $i < $activeGame['max_attempts']; $i++): ?>
                    <?php for ($j = 0; $j < $wordLength; $j++): ?>
                        <?php
                            $value = isset($attempts[$i]['attempt_word'][$j]) ? htmlspecialchars($attempts[$i]['attempt_word'][$j]) : '';
                            $disabled = ($i > $activeGame['attempts'] || $i < $activeGame['attempts']) ? 'disabled' : '';
                            $class = '';
                            if (isset($attempts[$i]['feedback'][$j])) {
                                switch ($attempts[$i]['feedback'][$j]) {
                                    case 2:
                                        $class = 'correct';
                                        break;
                                    case 1:
                                        $class = 'misplaced';
                                        break;
                                    case 0:
                                        $class = 'incorrect';
                                        break;
                                }
                            }
                        ?>
                        <input type="text" name="guess[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $value; ?>" <?php echo $disabled; ?> class="<?php echo $class; ?>" maxlength="1" required>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div>
        </div>

        <button class="primary-btn" type="submit"><h4>Submit Guess</h4></button>
    </form>

    <div class="game-action-container">
        <h2>Game Stats</h2>
        <br>
        <h3>Maximum points:</h3>
        <h3>Current points:</h3>
    </div>
</div> -->

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>
