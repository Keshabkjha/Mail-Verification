<?php
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

// Sanitize input
$user = htmlspecialchars($_POST['username']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Prepare and execute query
$response = array('username' => false, 'email' => false);

if ($user) {
    $sql_check_user = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql_check_user);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response['username'] = true;
    }
    $stmt->close();
}

if ($email) {
    $sql_check_email = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql_check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response['email'] = true;
    }
    $stmt->close();
}

$conn->close();

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
