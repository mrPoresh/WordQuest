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

<h2>Game Setup</h2>

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

        document.getElementById('points-info').innerText = `Maximum points: ${maxWinScore}, Minimum points: ${minWinScore}, Loose points: ${looseScore}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updatePointsInfo();
    });
</script>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>