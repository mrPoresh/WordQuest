<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';
require_once '../src/middleware/AuthMiddleware.php';


$authMiddleware = new AuthMiddleware($pdo);
$userId = $authMiddleware->checkSession();

if (!$userId) {
    header('Location: login.php');
    exit();
}

$userController = new UserController($pdo);

$user = $userController->getUserById($userId);
$score = $userController->getUserScore($userId);
$gameHistory = $userController->getUserGameHistory($userId);

$title = "User Page";
ob_start();

?>

<div class="page-wrapper">
    <div class="page-container">
        <h3><?php echo htmlspecialchars($user['username']); ?></h3>
        <h4><?php echo htmlspecialchars($user['email']); ?></h4>
        <h4>Current Score: <?php echo htmlspecialchars($score['current_score']); ?></h4>
        <h4>Last Score: <?php echo htmlspecialchars($score['last_score']); ?></h4>
        <br>
        <button onclick="logout()" class="btn-round medium warn"><h5>Logout</h5></button>
    </div>

    <div class="page-container">
        <table class="game-stats">
            <thead>
                <tr>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th style="text-align: center">Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gameHistory as $game): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($game['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($game['end_time'] ? $game['end_time'] : 'Null'); ?></td>
                        <td><?php echo htmlspecialchars($game['status']); ?></td>
                        <td style="text-align: center"><?php echo htmlspecialchars($game['status'] === 'won' ? $game['game_score'] ? $game['game_score'] : 0 : -$game['loose_score']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn-round medium primary" onclick="navigate('game.php')"><h5>Play</h5></button>
    </div>
</div>

<style>

.game-stats {
    width: 100%;
    height: 100%;

    text-align: left;
}

.game-stats th {
    padding: var(--padding-medium) 0;
}

.game-stats td {
    padding: var(--padding-small);
}

</style>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>