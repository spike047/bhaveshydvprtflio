<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "❌ Database connection failed: " . $conn->connect_error]);
    exit;
}

// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["error" => "❌ Invalid request! Only POST is allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "❌ Invalid JSON format!", "raw_input" => file_get_contents("php://input")]);
    exit;
}

if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "❌ Missing required fields!"]);
    exit;
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = trim($data['password']);

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(["error" => "❌ All fields are required!"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(["error" => "❌ SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "✅ User registered successfully!", "redirect" => "courses.html"]);
} else {
    echo json_encode(["error" => "❌ Error inserting data: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
