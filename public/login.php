<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';


$authController = new AuthController($pdo);

if (isset($_SESSION['auth_token'])) {
    $token = $_SESSION['auth_token'];
    error_log("SESSION: $token");
    $userId = $authController->verifyToken($token);

    if ($userId) {
        header('Location: index.php');
        exit();
    }
}

$title = "Login";
ob_start();

?>

<title>Login</title>
<link rel="stylesheet" href="assets/css/global.css">
    <div class="box-wrapper">
        <h2>Login</h2>
        <div id="error-message" style="color: red;"></div>
        <form id="login-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</div>


<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>