<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");


$input = json_decode(file_get_contents("php://input"), true);
$courseID = isset($input["courseID"]) ? trim($input["courseID"]) : "";
$action = isset($input["action"]) ? trim($input["action"]) : "";

$file = __DIR__ . '/../courses.json';

// Check file exists
if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Data file not found.']);
    exit;
}

// Read filedata
$jsonData = file_get_contents($file);
$courseData = json_decode($jsonData, true);

$updated = false;

// Find the course
foreach ($courseData as &$course) {
    if ($course['courseID'] === $courseID) {
        if ($action === 'like') {
            $course['likes'] += 1;
            $updated = true;
            break;
        } else if ($action === 'unlike') {
            $course['likes'] -= 1;
            $updated = true;
            break;
        } else if ($action === 'enroll') {
            $course['enrolled'] += 1;
            $updated = true;
            break;
        }
    }
}

if ($updated) {
    $result = file_put_contents($file, json_encode($courseData, JSON_PRETTY_PRINT));
    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write to file.']);
    } else {
        echo json_encode(['message' => 'Course updated successfully.']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Course not found.']);
}

?>