<?php

$title = "Home";
ob_start();

?>

<div class="home-wrapper">
    <div class="into-container">
        <div class="grid-container">
            <div class="grid-row">
                <div style="background-color: var(--primary-900)" class="grid-item"><h2>W</h2></div>
                <div style="background-color: var(--warn-600)" class="grid-item"><h2>O</h2></div>
                <div class="grid-item"><h2>R</h2></div>
                <div class="grid-item"><h2>D</h2></div>
            </div>

            <div class="grid-row">
                <div class="grid-item"></div>
                <div class="grid-item"></div>
                <div class="grid-item"></div>
                <div class="grid-item"></div>
            </div>

            <div class="grid-row">
                <div class="grid-item"></div>
                <div class="grid-item"></div>
                <div style="background-color: var(--warn-600)" class="grid-item"><h2>Q</h2></div>
                <div class="grid-item"><h2>U</h2></div>
            </div>

            <div class="grid-row">
                <div style="background-color: var(--primary-900)" class="grid-item"><h2>E</h2></div>
                <div style="background-color: var(--primary-900)" class="grid-item"><h2>S</h2></div>
                <div class="grid-item"><h2>T</h2></div>
                <div class="grid-item"></div>
            </div>
        </div>

        <button class="btn-round medium primary" onclick="navigate('game.php')"><h5>Try Game</h5></button>
    </div>

    <div class="about-container">
        <div class="about-section">
            <h2>About Project</h2>
            <br>
            <h4>WordQuest is an interactive word-guessing game inspired by popular word puzzle games. The aim of the project is to provide a fun and engaging experience where players guess words of varying lengths within a set number of attempts. This project marks my first foray into backend development using PHP, expanding my skill set beyond my prior experience with Angular.</h4>
            <br>
            <h3>Technologies Used</h3>
            <ul>
                <li><h4>Frontend: HTML, CSS, JavaScript (vanilla)</h4></li>
                <li><h4>Backend: PHP</h4></li>
                <li><h4>Database: MySQL</h4></li>
                <li><h4>Session Management: PHP Sessions</h4></li>
                <li><h4>Authentication: Token-based (JWT)</h4></li>
                <li><h4>Styling and Layout: Responsive design with CSS media</h4></li>
                <li><h4>Version Control: Git and GitHub</h4></li>
            </ul>
        </div>

        <div class="about-section">
            <h2>Project Features</h2>
                <ul>
                    <li><h4>User Authentication: Secure login and signup processes with token-based authentication.</h4></li>
                    <li><h4>Game Logic: Players can start a new game, make guesses, and receive feedback based on their inputs. The game keeps track of the number of attempts and provides feedback on correct and misplaced letters.</h4></li>
                    <li><h4>Scoring System: The game calculates scores based on the word length and the number of attempts. Points are awarded for correct guesses and deducted for incorrect ones.</h4></li>
                    <li><h4>Responsive Design: The game is designed to be fully responsive, providing an optimal experience on both desktop and mobile devices.</h4></li>
                    <li><h4>Session Management: Ensures that user sessions are securely managed, maintaining game state and user authentication across sessions.</h4></li>
                </ul>
        </div>
    </div>
</div>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>
