<?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $code = htmlspecialchars($_POST['code']);

    $sql = "SELECT * FROM users WHERE email = ? AND verification_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verification successful, update user status
        $sql_update = "UPDATE users SET is_verified = 1 WHERE email = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        header("Location: login_form.php?verified=1");
    } else {
        header("Location: verify.php?email=" . urlencode($email) . "&error=invalid_code");
    }
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
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
        .verification-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .verification-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .verification-container label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        .verification-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }
        .verification-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .verification-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .verification-container .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
        .verification-container .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }
        .verification-container p {
            text-align: center;
            color: #555;
        }
        .verification-container p a {
            color: #4CAF50;
            text-decoration: none;
        }
        .verification-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h2>Email Verification</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error">Invalid verification code. Please try again.</div>';
        }
        if (isset($_GET['resend'])) {
            if ($_GET['resend'] == 'success') {
                echo '<div class="success">A new verification code has been sent to your email.</div>';
            } elseif ($_GET['resend'] == 'failed') {
                echo '<div class="error">Failed to resend the verification code. Please try again.</div>';
            }
        }
        ?>
        <form action="verify.php" method="POST">
            <label for="code">Enter Verification Code</label>
            <input type="text" id="code" name="code" placeholder="Enter your verification code" required>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            <input type="submit" value="Verify">
        </form>
        <p>Didn't receive a code? <a href="resend_code.php?email=<?php echo urlencode($_GET['email']); ?>">Resend</a></p>
    </div>
</body>
</html>
