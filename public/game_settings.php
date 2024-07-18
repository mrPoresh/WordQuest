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
        <select id="word-length" name="word-length">
            <option value="4">4 letters</option>
            <option value="5">5 letters</option>
            <option value="6">6 letters</option>
            <option value="7">7 letters</option>
            <option value="8">8 letters</option>
        </select>
        <br>
        <button type="submit">Start Game</button>
    </form>
</div>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>

<!-- <?php

// require_once '../src/config/session.php';
// require_once '../src/config/logger.php';
// require_once '../src/middleware/AuthMiddleware.php';
// require_once '../src/controllers/UserController.php';
// require_once '../src/controllers/GameController.php';

// $logger = new Logger();
// $logger->console_log("game_settings.php");

// $authMiddleware = new AuthMiddleware();
// $authMiddleware->checkSession();

// $userController = new UserController();
// $user = $userController->getUser();

// if ($user) {
//     $username = $user['username'];
//     $token = $_SESSION['auth_token'];
    
//     $gameController = new GameController();
//     $activeGame = $gameController->loadGame($token, $username);

//     if ($activeGame) {
//         // Ask user to continue existing game
//         if (isset($_POST['continue'])) {
//             header('Location: game.php');
//             exit();
//         } elseif (isset($_POST['new_game'])) {
//             // Mark the current game as lost
//             $stmt = $pdo->prepare('UPDATE games SET status = "lost", end_time = NOW() WHERE id = ?');
//             $stmt->execute([$activeGame['id']]);
//             $activeGame = null;
//         }
//     }

//     if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['word_length'])) {
//         $wordLength = $_POST['word_length'];

//         if ($gameController->createGame($wordLength, $token)) {
//             header('Location: game.php');
//             exit();
//         } else {
//             echo "Failed to start the game.";
//         }
//     }
// }
// ?>

// <!DOCTYPE html>
// <html lang="en">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Game Setup</title>
//     <link rel="stylesheet" href="assets/css/style.css">
// </head>
// <body>
//     <h2>Game Setup</h2>
//     <?php if ($activeGame): ?>
//         <p>You have an active game. Would you like to continue?</p>
//         <form method="POST" action="">
//             <button type="submit" name="continue">Continue Game</button>
//             <button type="submit" name="new_game">Start New Game</button>
//         </form>
//     <?php else: ?>
//         <form method="POST" action="">
//             <label for="word_length">Select word length:</label>
//             <select id="word_length" name="word_length">
//                 <option value="4">4 letters</option>
//                 <option value="5">5 letters</option>
//                 <option value="6">6 letters</option>
//                 <option value="7">7 letters</option>
//                 <option value="8">8 letters</option>
//             </select>
//             <br>
//             <button type="submit">Start Game</button>
//         </form>
//     <?php endif; ?>
// </body>
</html> -->