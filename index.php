<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
        .registration-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .registration-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .registration-container input[type="text"], 
        .registration-container input[type="email"], 
        .registration-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .registration-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .registration-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .registration-container p {
            text-align: center;
        }
        .registration-container p a {
            color: #4CAF50;
            text-decoration: none;
        }
        .registration-container p a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin: 10px 0;
            text-align: center;
        }
        .success {
            color: green;
            margin: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Register</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error">';
            if ($_GET['error'] == 'exists') {
                echo "Username or email already exists.";
            } elseif ($_GET['error'] == 'failed') {
                echo "Registration failed. Please try again.";
            }
            echo '</div>';
        }

        if (isset($_GET['success'])) {
            echo '<div class="success">';
            if ($_GET['success'] == 1) {
                echo "Registration successful!";
            }
            echo '</div>';
        }
        ?>

        <form id="registrationForm" action="register.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <span id="usernameError" class="error"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <span id="emailError" class="error"></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const usernameError = document.getElementById('usernameError');
            const emailError = document.getElementById('emailError');

            function checkAvailability() {
                const username = usernameInput.value;
                const email = emailInput.value;

                if (username || email) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'check_availability.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.username) {
                                usernameError.textContent = "Username already exists.";
                            } else {
                                usernameError.textContent = "";
                            }
                            if (response.email) {
                                emailError.textContent = "Email already exists.";
                            } else {
                                emailError.textContent = "";
                            }
                        }
                    };
                    xhr.send('username=' + encodeURIComponent(username) + '&email=' + encodeURIComponent(email));
                }
            }
            usernameInput.addEventListener('input', checkAvailability);
            emailInput.addEventListener('input', checkAvailability);
        });
    </script>
</body>
</html>
