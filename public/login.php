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

<!-- <div class="login-container">
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
</div> -->

<div class="login-form-container">
    <h2>Login</h2>

    <form id="loginForm">
        <div class="form-input-container">
            <h4>email</h4>
            <input type="email" id="email" name="email" class="form-contact-input" maxlength="36" required>
        </div>

        <div class="form-input-container">
            <h4>password</h4>
            <input type="password" id="password" name="password" class="form-contact-input" maxlength="36" required>
        </div>

        <p>Don't have an account?<a href="signup.php">Sign up</a></p>

        <br>

        <button type="submit" class="btn-round medium primary"><h5>Submit</h5></button>
    </form>
</div>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>