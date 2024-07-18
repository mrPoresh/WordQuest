// Function to handle login

//  TODO: Handle auth errors => like err hint into forms

/* Auth */
/* ------------------------------------------------------------------------------- */

function login(email, password) {
    fetch('api/auth/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('auth_token', data.token);
            window.location.href = 'index.php';
        } else {
            document.getElementById('error-message').innerText = data.error;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to handle signup
function signup(username, email, password) {
    fetch('api/auth/signup.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: username, email: email, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('auth_token', data.token);
            window.location.href = 'index.php';
        } else {
            document.getElementById('error-message').innerText = data.error;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to handle logout
function logout() {
    fetch('api/auth/logout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('auth_token');
            window.location.href = 'login.php';
        }
    })
    .catch(error => console.error('Error:', error));
}

/* User */
/* ------------------------------------------------------------------------------- */

function getUser() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        console.error('No auth token found');
        return;
    }

    fetch('api/user/user_get.php', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            //console.log('User data:', data.user);
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

/* Game */
/* ------------------------------------------------------------------------------- */

function createGame(wordLength) {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        console.error('No auth token found');
        return;
    }

    fetch('api/game/create_game.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ wordLength: wordLength })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'game.php';
        } else {
            alert('Failed to start the game: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function endGame() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        console.error('No auth token found');
        return;
    }

    fetch('api/game/end_active_game.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('game-setup-section').style.display = 'block';
            document.getElementById('active-game-section').style.display = 'none';
        } else {
            alert('Failed to end the game: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function continueGame() {
    window.location.href = 'game.php';
}

/* Btns */
/* ------------------------------------------------------------------------------- */

const continueButton = document.getElementById('continue-game-btn');
if (continueButton) {
    continueButton.addEventListener('click', function() {
        continueGame();
    });
}

const endButton = document.getElementById('end-game-btn');
if (endButton) {
    endButton.addEventListener('click', function() {
        endGame();
    });
}

/* ------------------------------------------------------------------------------- */

// Event listener for login form
const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        login(email, password);
    });
}

// Event listener for signup form
const signupForm = document.getElementById('signup-form');
if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        signup(username, email, password);
    });
}

// Event listener for game setup form
const gameSetupForm = document.getElementById('game-setup-form');
if (gameSetupForm) {
    gameSetupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const wordLength = document.getElementById('word-length').value;
        createGame(wordLength);
    });
}
