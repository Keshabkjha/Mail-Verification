<?php
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

if (isset($_GET['email'])) {
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    // Generate new verification code
    $new_code = rand(100000, 999999);

    // Update the user's verification code in the database
    $sql_update_code = "UPDATE users SET verification_code = ? WHERE email = ?";
    $stmt = $conn->prepare($sql_update_code);
    $stmt->bind_param("ss", $new_code, $email);

    if ($stmt->execute()) {
        // Send the new code via email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Your Mail'; 
            $mail->Password   = 'your password'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('Your Mail', 'Domain Name');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true); 
            $mail->Subject = 'Resend: Your Verification Code';
            $mail->Body    = 'Your new verification code is: <b>' . $new_code . '</b>';

            $mail->send();
            header("Location: verify.php?email=" . urlencode($email) . "&resend=success");
        } catch (Exception $e) {
            header("Location: verify.php?email=" . urlencode($email) . "&resend=failed");
        }
    } else {
        header("Location: verify.php?email=" . urlencode($email) . "&error=update_failed");
    }
    exit();
}

$stmt->close();
$conn->close();
?>
