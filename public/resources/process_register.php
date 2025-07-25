<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$host = "feenix-mariadb.swin.edu.au";
$user = "s104475686"; 
$pwd = "291205"; 
$sql_db = "s104475686_db"; 

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    http_response_code(500); // Server error
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Get raw POST data
$input = json_decode(file_get_contents("php://input"), true);

$firstName = trim($input["firstName"]);
$lastName = trim($input["lastName"]);
$dob = trim($input["dob"]);
$username = trim($input["username"]);
$password = trim($input["password"]);
$email = trim($input["email"]);
$role = trim($input["role"]);

$table = $role === 'learner' ? 'learners' : 'instructors';

// Check if username or email already exists in Learners table
$checkL = $conn->prepare("SELECT * FROM learners WHERE username = ? OR email = ?");
$checkL->bind_param("ss", $username, $email);
$checkL->execute();
$resultL = $checkL->get_result();

// Check if username or email already exists in Instructors table
$checkI = $conn->prepare("SELECT * FROM instructors WHERE username = ? OR email = ?");
$checkI->bind_param("ss", $username, $email);
$checkI->execute();
$resultI = $checkI->get_result();

if ($resultL->num_rows > 0 || $resultI->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Username and/or email already exists"]);
    exit();
}

// Insert new user
$stmt = $conn->prepare(
    "INSERT INTO $table (firstName, lastName, dob, username, password, email) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssssss", $firstName, $lastName, $dob, $username, $password, $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);

    $response = [
        "role" => $role,
        "firstName" => $user["firstName"],
        "lastName" => $user["lastName"],
        "dob" => $user["dob"],
        "username" => $user["username"],
        "email" => $user["email"]
    ];
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to register user."]);
}

echo json_encode($response);

$conn->close();
?>