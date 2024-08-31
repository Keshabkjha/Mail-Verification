<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-container input[type="text"], 
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .login-container p {
            text-align: center;
        }
        .login-container p a {
            color: #4CAF50;
            text-decoration: none;
        }
        .login-container p a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error">';
            if ($_GET['error'] == 'empty_fields') {
                echo "Please fill in all fields.";
            } elseif ($_GET['error'] == 'user_not_found') {
                echo "User not found.";
            } elseif ($_GET['error'] == 'incorrect_password') {
                echo "Incorrect password.";
            }
            echo '</div>';
        }
        ?>
        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="index.php">Register here</a></p>
    </div>
</body>
</html>
