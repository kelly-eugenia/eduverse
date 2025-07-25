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
$learnerID = isset($input["learnerID"]) ? trim($input["learnerID"]) : "";
$courseID = isset($input["courseID"]) ? trim($input["courseID"]) : "";

$check = $conn->prepare("SELECT * FROM enrolledCourses WHERE learnerID = ? AND courseID = ?");
$check->bind_param("is", $learnerID, $courseID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "User is already enrolled."]);
    exit();
}

$enrolledDate = date("Y-m-d");

// Insert new enrolled course
$stmt = $conn->prepare(
    "INSERT INTO enrolledCourses (learnerID, courseID, enrolledDate, completedDate, progress) VALUES (?, ?, ?, NULL, 0)"
);
$stmt->bind_param("iss", $learnerID, $courseID, $enrolledDate);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Enrolled successfully.",
        "enrollment" => [
            "learnerID" => $learnerID,
            "courseID" => $courseID,
            "enrolledDate" => $enrolledDate,
            "completedDate" => null,
            "progress" => 0
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to enroll.", "details" => $stmt->error]);
}

$conn->close();

?>