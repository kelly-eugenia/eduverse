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

$input = json_decode(file_get_contents("php://input"), true);
$instructorID = isset($input["instructorID"]) ? trim($input["instructorID"]) : "";
$courseID = isset($input["courseID"]) ? trim($input["courseID"]) : "";
$date = isset($input["date"]) ? $input["date"] : "";

$check = $conn->prepare("SELECT * FROM addedCourses WHERE instructorID = ? AND courseID = ?");
$check->bind_param("is", $instructorID, $courseID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Course is already added."]);
    exit();
}

// Insert new course
$stmt = $conn->prepare(
    "INSERT INTO addedCourses (instructorID, courseID, date) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $instructorID, $courseID, $date);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Added course successfully.",
        "course" => [
            "instructorID" => $instructorID,
            "courseID" => $courseID
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to add course.", "details" => $stmt->error]);
}

$conn->close();
?>