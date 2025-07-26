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

$title = isset($input["title"]) ? trim($input["title"]) : "";
$category = isset($input["category"]) ? trim($input["category"]) : "";
$instructor = isset($input["instructor"]) ? trim($input["instructor"]) : "";
$difficulty = isset($input["difficulty"]) ? trim($input["difficulty"]) : "";
$skills = isset($input["skills"]) ? $input["skills"] : [];
$description = isset($input["description"]) ? trim($input["description"]) : "";
$enrolled = 0;
$ratings = 0;
$likes = 0;
$units = isset($input["units"]) ? $input["units"] : [];
$lastUpdated = date("Y-m-d");

// Calculate total hours
$hours = 0;
foreach ($units as $unit) {
    $hours += isset($unit["hour"]) ? floatval($unit["hour"]) : 0;
}

// Calculate duration
$duration = floor($hours / 10);

// Image URL based on category
if ($category === "Business") {
    $imageURL = "/assets/business.jpg";
} else if ($category === "IT & Computer Science") {
    $imageURL = "/assets/cs.jpg";
} else if ($category === "Design") {
    $imageURL = "/assets/design.jpg";
} else if ($category === "Finance") {
    $imageURL = "/assets/finance.jpg";
} else if ($category === "Marketing") {
    $imageURL = "/assets/marketing.jpg";
}

// Generate courseID
function generateCourseID($category) {
    $getNumber = strval(rand(1000, 3099)); // Convert to string

    if ($category === "Business") {
        $courseID = "BS" . $getNumber;
    } else if ($category === "IT & Computer Science") {
        $courseID = "IT" . $getNumber;
    } else if ($category === "Design") {
        $courseID = "DS" . $getNumber;
    } else if ($category === "Finance") {
        $courseID = "FN" . $getNumber;
    } else if ($category === "Marketing") {
        $courseID = "MK" . $getNumber;
    }

    return $courseID;
}

$courseID = generateCourseID($category);
$unique = false;
$changed = false;

// Check if courseID already exists
while ($unique === false) {
    foreach ($courseData as &$course) {
        if ($course['courseID'] === $courseID) {
            $courseID = generateCourseID($category);
            $changed = true;
        }
    }
    if ($changed === false) {
        $unique = true;
    } else {
        $changed = false;
    }
}

// Create new course object
$newCourse = [
    "courseID" => (string)$courseID,
    "title" => $title,
    "category" => $category,
    "instructor" => $instructor,
    "duration" => (int)$duration,
    "hours" => (float)$hours,
    "difficulty" => $difficulty,
    "skills" => $skills,
    "description" => $description,
    "enrolled" => $enrolled,
    "ratings" => $ratings,
    "likes" => $likes,
    "units" => $units,
    "imageURL" => $imageURL,
    "lastUpdated" => $lastUpdated
];

// Add to existing data
$courseData[] = $newCourse;

// Save back to the JSON file
if (file_put_contents($file, json_encode($courseData, JSON_PRETTY_PRINT))) {
    echo json_encode([
        "success" => true,
        "message" => "Course added successfully.",
        "courseID" => $courseID,
        "date" => $lastUpdated
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to write to file.']);
}
?>