<?php
session_start(); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pass = $_POST['password'];

// Check if username or email already exists
$sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ss", $user, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username or email already exists
    header("Location: index.php?error=exists");
    exit();
} else {
    // Generate verification code
    $verification_code = rand(100000, 999999);
    
    // Insert new user with verification code
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $user, $email, $hashed_password, $verification_code);
    
    if ($stmt->execute() === TRUE) {
        // Send verification email
        sendCode($email, 'Email Verification', $verification_code);
        header("Location: verify.php?email=" . urlencode($email));
    } else {
        header("Location: index.php?error=failed");
    }
    exit();
}

$stmt->close();
$conn->close();

// Function to send verification code
function sendCode($email, $subject, $code) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Disable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'username';                     // SMTP username
        $mail->Password   = 'your password';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
        $mail->Port       = 465;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom('username', 'domain');
        $mail->addAddress($email); 
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = 'Your verification code is: <b>' . $code . '</b>';
    
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

