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
$action = isset($input["action"]) ? trim($input["action"]) : "";

$check = $conn->prepare("SELECT * FROM likedCourses WHERE learnerID = ? AND courseID = ?");
$check->bind_param("is", $learnerID, $courseID);
$check->execute();
$result = $check->get_result();

// Like course
if ($action === "like" && $result->num_rows === 0) {

    // Insert new liked course
    $stmt = $conn->prepare(
        "INSERT INTO likedCourses (learnerID, courseID) VALUES (?, ?)"
    );
    $stmt->bind_param("is", $learnerID, $courseID);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Liked successfully."
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to like.", "details" => $stmt->error]);
    }
}
// Unlike course
else if ($action === "unlike" && $result->num_rows === 1) {

    // Remove liked course
    $stmt = $conn->prepare(
        "DELETE FROM likedCourses WHERE learnerID = ? AND courseID = ?"
    );
    $stmt->bind_param("is", $learnerID, $courseID);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Unliked successfully."
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to unlike.", "details" => $stmt->error]);
    }
}

$conn->close();
?>