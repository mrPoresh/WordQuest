<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';
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

$title = "Game Settings";
ob_start();

?>

<!-- <h2>Game Setup</h2>

<div id="active-game-section" style="display: <?php echo $activeGame ? 'block' : 'none'; ?>">
    <p>You have an active game. Do you want to continue?</p>
    <button id="continue-game-btn">Yes</button>
    <button id="end-game-btn">No</button>
</div>

<div id="game-setup-section" style="display: <?php echo $activeGame ? 'none' : 'block'; ?>;">
    <form id="game-setup-form">
        <label for="word-length">Select word length:</label>
        <select id="word-length" name="word-length" onchange="updatePointsInfo()">
            <option value="4">4 letters</option>
            <option value="5">5 letters</option>
            <option value="6">6 letters</option>
            <option value="7">7 letters</option>
            <option value="8">8 letters</option>
        </select>
        <br>
        <label for="max-attempts">Select max attempts:</label>
        <select id="max-attempts" name="max-attempts" onchange="updatePointsInfo()">
            <option value="3">3 attempts</option>
            <option value="4">4 attempts</option>
            <option value="5">5 attempts</option>
            <option value="6">6 attempts</option>
            <option value="7">7 attempts</option>
            <option value="8">8 attempts</option>
        </select>
        <br>
        <p id="points-info"></p>
        <br>
        <button type="submit">Start Game</button>
    </form>
</div> -->

<div class="page-wrapper">
    <div id="question" class="active-game-seession" style="display: <?php echo $activeGame ? 'flex' : 'none'; ?>">
        <h4 style="color: var(--accent-contrast-900);">You have an active game. Do you want to continue?</h4>
        <div style="display: flex; flex-direction: row; gap: 16px;">
            <button class="btn-round medium primary" id="continue-game-btn"><h5>Yes</h5></button>
            <button class="btn-round medium warn" id="end-game-btn"><h5>No</h5></button>
        </div>
    </div>

    <div id="settings" class="page-container" style="display: <?php echo $activeGame ? 'none' : 'flex'; ?>">
        <h2>Game SetUp</h2>
        <form id="gameSetupForm">
            <div class="select-container">
                <label for="word-length"><h4>Select word length: </h4></label>
                <select class="select-primary" id="word-length" name="word-length" onchange="updatePointsInfo()">
                    <option value="4">4 letters</option>
                    <option value="5">5 letters</option>
                    <option value="6">6 letters</option>
                    <option value="7">7 letters</option>
                    <option value="8">8 letters</option>
                </select>

                <label for="max-attempts"><h4>Select max attempts: </h4></label>
                <select class="select-primary" id="max-attempts" name="max-attempts" onchange="updatePointsInfo()">
                    <option value="3">3 attempts</option>
                    <option value="4">4 attempts</option>
                    <option value="5">5 attempts</option>
                    <option value="6">6 attempts</option>
                    <option value="7">7 attempts</option>
                    <option value="8">8 attempts</option>
                </select>
                <br>
                <h4 id="points-max"></h4>
                <h4 id="points-min"></h4>
                <h4 id="points-los"></h4>
            </div>

            <button type="submit" class="btn-round medium primary"><h5>Play</h5></button>
        </form>
    </div>

    <div class="page-container" style="display: <?php echo $activeGame ? 'none' : 'flex'; ?>">
        <h2>Game Rules</h2>
        <div class="rules-list">
            <h3>Start the Game</h3>

            <h4>Select word length (4 to 8 letters) and number of attempts (3 to 8).
            The game begins with a randomly chosen word of the selected length.</h4>
            <br>
            <h3>Gameplay</h3>

            <h4>Enter your guess in the provided field.
            After each attempt, you will receive feedback:
            Green: Letter is in the correct position.
            Yellow: Letter is in the word but in the wrong position.
            Gray: Letter is not in the word.</h4>
            <br>
            <h3>End of Game</h3>

            <h4>Win: If you guess the word before running out of attempts, you win.</h4>
            <h4>Lose: If you run out of attempts without guessing the word, you lose.</h4>
            <!-- <br>
            <h3>Scoring</h3>
            <h4>Points are awarded for each correct letter in the correct position.
            Bonus points for remaining attempts.
            Points are deducted in case of a loss.</h4> -->
        </div>
    </div>
</div>

<script>
    function calculatePoints(wordLength, attempts) {
        const baseScore = wordLength * 10;
        const maxWinScore = baseScore * 10;
        const minWinScore = maxWinScore - baseScore * (attempts - 3)
        const looseScore = -maxWinScore / 2;

        return { maxWinScore, minWinScore, looseScore };
    }

    function updatePointsInfo() {
        const wordLength = document.getElementById('word-length').value;
        const maxAttempts = document.getElementById('max-attempts').value;

        const { maxWinScore, minWinScore, looseScore } = calculatePoints(wordLength, maxAttempts);

        document.getElementById('points-max').innerText = `Maximum points: ${maxWinScore}`;
        document.getElementById('points-min').innerText = `Minimum points: ${minWinScore}`;
        document.getElementById('points-los').innerText = `Loose points: ${looseScore}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updatePointsInfo();
    });
</script>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>