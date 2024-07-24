# Word Quest Project

Welcome to the Word Quest project! This document covers the functionality, technical details, project goals, and full instructions on how to get started, including setting up error logs and other configurations.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Configuration](#configuration)
  - [Running the Application](#running-the-application)
  - [Setting Up Error Logs](#setting-up-error-logs)
- [Gameplay Instructions](#gameplay-instructions)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

Word Quest is a word-guessing game inspired by Wordle. Players attempt to guess a secret word within a limited number of attempts. The project aims to provide a fun and interactive experience while showcasing PHP and MySQL skills.

## Features

- User authentication (login, signup, logout)
- Random word selection based on chosen word length
- Multiple attempts to guess the word
- Feedback on guesses (correct letter and position, correct letter wrong position)
- Scoring system
- Game history
- Dark and light theme toggle

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP, MySQL
- **Libraries**: [Font Awesome](https://fontawesome.com/), [Google Fonts](https://fonts.google.com/)

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (e.g., Apache)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/wordquest.git
   cd wordquest
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

### Configuration

1. **Database Configuration**:
   Create a MySQL database and import the SQL schema provided in `schema.sql`.

2. **Environment Configuration**:
   Create the `.env` with your database credentials:

    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE={(for example) => }wordquest
    DB_USERNAME=
    DB_PASSWORD=
    DB_CHARSET=

### Running the Application

1. Start your web server and ensure it's pointing to the `public` directory.
2. Open your web browser and navigate to `http://localhost`.

### Setting Up Error Logs

To set up error logs for PHP, follow these steps:

1. Open your `php.ini` file. If you're using XAMPP, it's typically located at `C:\xampp\php\php.ini`.

2. Find and update the following lines:
   ```ini
   display_errors = Off
   log_errors = On
   error_log = "C:\xampp\php\logs\php_error_log"
   ```

3. Restart your web server to apply the changes.

## Gameplay Instructions

1. **Login or Signup**: Create a new account or log in with your existing credentials.
2. **Game Settings**: Choose the word length and the number of attempts.
3. **Start Game**: Start guessing the word. Feedback will be provided after each guess:
   - Correct position and letter
   - Correct letter, wrong position
   - Wrong letter
4. **Scoring**: Scores are calculated based on the length of the word and the number of attempts taken.

## Contributing

Contributions are welcome! Please fork the repository and create a pull request with your changes.

## License

This project is licensed under the MIT License.