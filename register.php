<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json");

// Debugging: Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "user_auth";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method!"]);
    exit;
}

// Read form data
$name = trim($_POST["name"] ?? '');
$email = trim($_POST["email"] ?? '');
$password = trim($_POST["password"] ?? '');

// Debugging: Log received data
error_log("Received Registration Data: " . print_r($_POST, true));

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "error" => "All fields are required!"]);
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into the database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "redirect" => "courses.html"]);
} else {
    echo json_encode(["success" => false, "error" => "Registration failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
