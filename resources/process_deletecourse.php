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

if ($result->num_rows <= 0) {
    http_response_code(409);
    echo json_encode(["error" => "Course not found."]);
    exit();
}

// Delete course from addedCourses
$stmtA = $conn->prepare(
    "DELETE FROM addedCourses WHERE instructorID = ? AND courseID = ?"
);
$stmtA->bind_param("is", $instructorID, $courseID);

// Delete course from likedCourses
$stmtL = $conn->prepare(
    "DELETE FROM likedCourses WHERE courseID = ?"
);
$stmtL->bind_param("s", $courseID);

// Delete course from enrolledCourses
$stmtE = $conn->prepare(
    "DELETE FROM enrolledCourses WHERE courseID = ?"
);
$stmtE->bind_param("s", $courseID);

if ($stmtA->execute() && $stmtL->execute() && $stmtE->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Deleted course successfully."
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete course.", "details" => $stmt->error]);
}

$conn->close();
?>