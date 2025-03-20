<?php
header("Content-Type: application/json");

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "❌ Only POST requests are allowed!"]);
    http_response_code(405); // Method Not Allowed
    exit;
}

// Read raw JSON input
$raw_input = file_get_contents("php://input");
$data = json_decode($raw_input, true);

// Debugging: Log input
error_log("🔹 Signup Raw Input: " . $raw_input);
error_log("🔹 Signup Decoded Data: " . json_encode($data));

// Validate input
if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "❌ All fields are required!", "raw_input" => $raw_input]);
    exit;
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = trim($data['password']);

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(["error" => "❌ All fields must be filled!"]);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Database connection
include "db.php";

// Check if email already exists
$check_query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["error" => "❌ Email already exists!"]);
    exit;
}

// Insert new user
$query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $name, $email, $hashed_password);

// Execute the query and check for errors
if ($stmt->execute()) {
    echo json_encode(["success" => true, "redirect" => "index.html"]);
} else {
    echo json_encode(["error" => "❌ Registration failed!", "sql_error" => $stmt->error]);
}

// Debugging: Log SQL errors
error_log("🔹 SQL Error: " . $stmt->error);

$stmt->close();
$conn->close();
?>
