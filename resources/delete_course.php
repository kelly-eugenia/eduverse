<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

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

// Get raw POST data
$input = json_decode(file_get_contents("php://input"), true);
$courseID = isset($input["courseID"]) ? trim($input["courseID"]) : "";

// Update array to remove course
$updatedCourses = array_filter($courseData, function ($course) use ($courseID) {
    return $course['courseID'] !== $courseID;
});

// Save back to the JSON file
if ($courseData != $updatedCourses) {
    $result = file_put_contents($file, json_encode($updatedCourses, JSON_PRETTY_PRINT));
    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write to file.']);
    } else {
        echo json_encode(['message' => 'Course deleted successfully.']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Course not found.']);
}
?>