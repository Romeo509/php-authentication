# Simple Authentication System (PHP & MySQL)

This project is a simple authentication system built with PHP and MySQL. It includes user login, signup, and a dashboard, all within a single file. The system automatically creates the required database and table if they don't exist, ensuring a smooth setup process.

## index.php Code

```php
<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password
$dbname = "auth_system";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully or already exists!";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    // echo "Table users created successfully or already exists!";
} else {
    die("Error creating table: " . $conn->error);
}

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "User not found!";
    }
}

// Handle Signup
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if ($conn->query($sql) === TRUE) {
        $success = "Registration successful! You can now log in.";
    } else {
        $error = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { width: 300px; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .toggle { text-align: center; margin-top: 10px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2 id="form-title">Login</h2>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>

    <!-- Login Form -->
    <form action="" method="POST" id="login-form">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <!-- Signup Form -->
    <form action="" method="POST" id="signup-form" style="display: none;">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <div class="toggle">
        <p id="toggle-text" onclick="toggleForm()">Don't have an account? Sign Up</p>
    </div>
</div>

<script>
    function toggleForm() {
        var loginForm = document.getElementById("login-form");
        var signupForm = document.getElementById("signup-form");
        var formTitle = document.getElementById("form-title");
        var toggleText = document.getElementById("toggle-text");

        if (loginForm.style.display === "none") {
            loginForm.style.display = "block";
            signupForm.style.display = "none";
            formTitle.textContent = "Login";
            toggleText.textContent = "Don't have an account? Sign Up";
        } else {
            loginForm.style.display = "none";
            signupForm.style.display = "block";
            formTitle.textContent = "Sign Up";
            toggleText.textContent = "Already have an account? Log In";
        }
    }
</script>

</body>
</html>
```

##1. Database and Table Setup
Upon loading, the script checks if the auth_system database and the users table exist. If not, they are automatically created.
The users table consists of the following fields:
id: The primary key, auto-incremented.
username: A unique identifier for the user.
password: The hashed password.
email: The user's email.
created_at: A timestamp for the account creation.
##2. Login Functionality
The user enters their username and password in the login form.
When the form is submitted, the system checks the database for a matching username.
If a match is found, it uses password_verify to compare the entered password with the hashed password stored in the database.
If the credentials are valid, the user is redirected to a dashboard page (you can create this separately as dashboard.php).
##3. Signup Functionality
The user enters a username, email, and password in the signup form.
The password is hashed using password_hash() before storing it in the database.
After successful registration, a success message is displayed and the user can log in.
##4. Form Toggling
The login and signup forms are in the same page, and a JavaScript function allows the user to toggle between them.
When the user clicks on the "Don't have an account? Sign Up" or "Already have an account? Log In" link, the appropriate form is displayed.

##Features
User Authentication: Provides login and signup functionality.
Database Setup: Automatically creates the database and table if they don't exist.
Password Security: Passwords are securely hashed using PHP’s password_hash() and password_verify().
Responsive Design: Simple and clean UI with a form toggle.

##Requirements
PHP 7.0 or higher
MySQL


