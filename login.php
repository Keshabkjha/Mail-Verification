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

// Sanitize user inputs
$user = htmlspecialchars($_POST['username']);
$pass = $_POST['password'];

// Validate input
if (empty($user) || empty($pass)) {
    header("Location: login_form.php?error=empty_fields");
    exit();
}

// Prepare and execute query to check user credentials
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($pass, $row['password'])) {
        // Password is correct, start a session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: dashboard.php?login=success");
        exit();
    } else {
        // Password is incorrect
        header("Location: login_form.php?error=incorrect_password");
        exit();
    }
} else {
    // Username doesn't exist
    header("Location: login_form.php?error=user_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>
