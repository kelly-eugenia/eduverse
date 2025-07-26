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
$title = isset($input["title"]) ? trim($input["title"]) : "";
$difficulty = isset($input["difficulty"]) ? trim($input["difficulty"]) : "";
$skills = isset($input["skills"]) ? $input["skills"] : [];
$description = isset($input["description"]) ? trim($input["description"]) : "";
$units = isset($input["units"]) ? $input["units"] : [];
$lastUpdated = date("Y-m-d");

// Calculate total hours
$hours = 0;
foreach ($units as $unit) {
    $hours += isset($unit["hour"]) ? floatval($unit["hour"]) : 0;
}
// Calculate duration
$duration = floor($hours / 10);


$updated = false;

// Find the course and edit
foreach ($courseData as &$course) {
    if ($course['courseID'] === $courseID) {
        $course['title'] = $title;
        $course['duration'] = $duration;
        $course['hours'] = $hours;
        $course['difficulty'] = $difficulty;
        $course['skills'] = $skills;
        $course['description'] = $description;
        $course['units'] = $units;
        $course['lastUpdated'] = $lastUpdated;

        $updated = true;
        break;
    }
}

// Save back to the JSON file
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